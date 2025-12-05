<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Mitra;
use App\Models\Restaurant;
use Illuminate\Support\Str;

class MitraBusinessSynchronizer
{
    /**
     * Sinkronkan data bisnis mitra ke tabel publik (destinations/hotels/restaurants).
     */
    public static function sync(Mitra $mitra, array $categoryIds = []): void
    {
        match ($mitra->business_type) {
            'hotel' => self::syncHotel($mitra, $categoryIds),
            'restoran' => self::syncRestaurant($mitra, $categoryIds),
            default => self::syncDestination($mitra, $categoryIds),
        };
    }

    /**
     * Hapus listing publik ketika data mitra dihapus.
     */
    public static function deleteLinkedListing(Mitra $mitra): void
    {
        match ($mitra->business_type) {
            'hotel' => Hotel::where('user_id', $mitra->user_id)->delete(),
            'restoran' => Restaurant::where('user_id', $mitra->user_id)->delete(),
            default => Destination::where('user_id', $mitra->user_id)->delete(),
        };
    }

    protected static function syncDestination(Mitra $mitra, array $categoryIds): void
    {
        $destination = Destination::firstOrNew(['user_id' => $mitra->user_id]);

        if (!$destination->exists || empty($destination->slug)) {
            $destination->slug = self::generateSlug($mitra->business_name);
        }

        $destination->fill([
            'city_id'        => $mitra->city_id,
            'category_id'    => self::defaultCategoryId(),
            'name'           => $mitra->business_name,
            'description'    => $mitra->business_description ?: 'Belum ada deskripsi',
            'address'        => $mitra->business_address ?: 'Alamat belum diisi',
            'entrance_fee'   => $mitra->ticket_price, // harga tiket dari mitra
            'contact_phone'  => $mitra->contact_phone,
            'contact_email'  => $mitra->contact_email,
            'website'        => $mitra->website,
            'thumbnail'      => $mitra->thumbnail,
        ]);

        $destination->status = $mitra->status;
        $destination->is_active = $mitra->status === 'approved';
        $destination->save();

        self::syncCategoriesForModel($mitra, $destination, $categoryIds);
    }

    protected static function syncHotel(Mitra $mitra, array $categoryIds): void
    {
        $hotel = Hotel::firstOrNew(['user_id' => $mitra->user_id]);

        if (!$hotel->exists || empty($hotel->slug)) {
            $hotel->slug = self::generateSlug($mitra->business_name);
        }

        $hotel->fill([
            'city_id'              => $mitra->city_id,
            'name'                 => $mitra->business_name,
            'description'          => $mitra->business_description ?: 'Belum ada deskripsi',
            'address'              => $mitra->business_address ?: 'Alamat belum diisi',
            'price_per_night_min'  => $mitra->room_price_single,
            'price_per_night_max'  => $mitra->room_price_double,
            'contact_phone'        => $mitra->contact_phone,
            'contact_email'        => $mitra->contact_email,
            'website'              => $mitra->website,
            'thumbnail'            => $mitra->thumbnail,
        ]);

        $hotel->status = $mitra->status;
        $hotel->is_active = $mitra->status === 'approved';
        $hotel->save();

        self::syncCategoriesForModel($mitra, $hotel, $categoryIds);
    }

    protected static function syncRestaurant(Mitra $mitra, array $categoryIds): void
    {
        $restaurant = Restaurant::firstOrNew(['user_id' => $mitra->user_id]);

        if (!$restaurant->exists || empty($restaurant->slug)) {
            $restaurant->slug = self::generateSlug($mitra->business_name);
        }

        $restaurant->fill([
            'city_id'       => $mitra->city_id,
            'name'          => $mitra->business_name,
            'description'   => $mitra->business_description ?: 'Belum ada deskripsi',
            'address'       => $mitra->business_address ?: 'Alamat belum diisi',
            'contact_phone' => $mitra->contact_phone,
            'contact_email' => $mitra->contact_email,
            'website'       => $mitra->website,
            'thumbnail'     => $mitra->thumbnail,
        ]);

        // Selalu sinkronkan rentang harga dari reservation_price terbaru bila ada.
        if (!is_null($mitra->reservation_price)) {
            $restaurant->average_price_min = $mitra->reservation_price;
            $restaurant->average_price_max = $mitra->reservation_price;
        }

        $restaurant->status = $mitra->status;
        $restaurant->is_active = $mitra->status === 'approved';
        $restaurant->save();

        self::syncCategoriesForModel($mitra, $restaurant, $categoryIds);
    }

    protected static function generateSlug(string $name): string
    {
        return Str::slug($name) . '-' . Str::lower(Str::random(6));
    }

    protected static function defaultCategoryId(): int
    {
        // Pastikan selalu ada satu kategori default untuk destinasi mitra
        $category = Category::firstOrCreate(
            ['slug' => 'wisata-mitra'],
            [
                'name' => 'Wisata Mitra',
                'description' => 'Destinasi yang dikelola oleh mitra ExploreNesia',
                'icon' => '📍',
            ]
        );

        return $category->id;
    }

    protected static function syncCategoriesForModel(Mitra $mitra, $model, array $categoryIds): void
    {
        $primaryCategoryId = self::primaryCategoryIdForMitra($mitra);

        $allCategoryIds = array_unique(array_filter([
            ...$categoryIds,
            $primaryCategoryId,
        ]));

        if (empty($allCategoryIds)) {
            return;
        }

        $pivotData = [];
        foreach ($allCategoryIds as $id) {
            $pivotData[$id] = ['is_primary' => $id === $primaryCategoryId];
        }

        $model->categories()->sync($pivotData);
    }

    protected static function primaryCategoryIdForMitra(Mitra $mitra): int
    {
        $slug = match ($mitra->business_type) {
            'hotel' => 'hotel',
            'restoran' => 'restoran',
            default => 'destinasi',
        };

        $category = Category::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => ucfirst($slug),
                'description' => 'Kategori utama untuk ' . $slug,
                'icon' => '📍',
            ]
        );

        return $category->id;
    }
}


