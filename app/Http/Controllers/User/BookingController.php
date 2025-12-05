<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Simpan pemesanan untuk sebuah destinasi.
     */
    public function storeDestination(Request $request, string $slug)
    {
        $destination = Destination::where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $unitPrice   = $destination->entrance_fee ?? 0;
        $quantity    = $validated['quantity'];
        $totalPrice  = $unitPrice * $quantity;
        $discount    = 0;
        $finalPrice  = $totalPrice - $discount;

        Booking::create([
            'user_id'        => Auth::id(),
            'bookable_type'  => Destination::class,
            'bookable_id'    => $destination->id,
            'booking_date'   => now()->toDateString(),
            'visit_date'     => $validated['visit_date'],
            'quantity'       => $quantity,
            'total_price'    => $totalPrice,
            'discount_amount'=> $discount,
            'final_price'    => $finalPrice,
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('user.destinations.show', $destination->slug)
            ->with('success', 'Pemesanan berhasil dibuat! Mitra akan segera memproses pesanan Anda.');
    }

    /**
     * Simpan pemesanan untuk sebuah hotel.
     */
    public function storeHotel(Request $request, string $slug)
    {
        $hotel = Hotel::where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
            'room_type'  => 'required|in:single,double',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:1000',
        ]);

        // Pilih harga berdasarkan tipe kamar
        $unitPrice = $validated['room_type'] === 'double'
            ? ($hotel->price_per_night_max ?? $hotel->price_per_night_min ?? 0)
            : ($hotel->price_per_night_min ?? 0);
        $quantity    = $validated['quantity'];
        $totalPrice  = $unitPrice * $quantity;
        $discount    = 0;
        $finalPrice  = $totalPrice - $discount;

        Booking::create([
            'user_id'        => Auth::id(),
            'bookable_type'  => Hotel::class,
            'bookable_id'    => $hotel->id,
            'booking_date'   => now()->toDateString(),
            'visit_date'     => $validated['visit_date'],
            'quantity'       => $quantity,
            'total_price'    => $totalPrice,
            'discount_amount'=> $discount,
            'final_price'    => $finalPrice,
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('user.hotels.show', $hotel->slug)
            ->with('success', 'Pemesanan hotel berhasil dibuat! Mitra akan segera memproses pesanan Anda.');
    }

    /**
     * Simpan pemesanan untuk sebuah restoran.
     */
    public function storeRestaurant(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $unitPrice   = $restaurant->average_price_min ?? 0;
        $quantity    = $validated['quantity'];
        $totalPrice  = $unitPrice * $quantity;
        $discount    = 0;
        $finalPrice  = $totalPrice - $discount;

        Booking::create([
            'user_id'        => Auth::id(),
            'bookable_type'  => Restaurant::class,
            'bookable_id'    => $restaurant->id,
            'booking_date'   => now()->toDateString(),
            'visit_date'     => $validated['visit_date'],
            'quantity'       => $quantity,
            'total_price'    => $totalPrice,
            'discount_amount'=> $discount,
            'final_price'    => $finalPrice,
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('user.restaurants.show', $restaurant->slug)
            ->with('success', 'Pemesanan restoran berhasil dibuat! Mitra akan segera memproses pesanan Anda.');
    }
}




