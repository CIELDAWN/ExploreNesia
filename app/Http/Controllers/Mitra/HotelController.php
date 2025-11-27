<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('city')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('mitra.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('mitra.hotels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'nullable|string', // Accept city name or ID
            'description' => 'required|string',
            'address' => 'required|string',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'price_per_night_min' => 'nullable|numeric|min:0',
            'price_per_night_max' => 'nullable|numeric|min:0',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'total_rooms' => 'nullable|integer|min:1',
            'thumbnail' => 'nullable|string',
            'facilities' => 'nullable|array',
        ]);

        // Handle city selection - just set to null for now
        if (empty($data['city_id']) || !is_numeric($data['city_id'])) {
            $data['city_id'] = null;
        }

        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['name']);
        $data['status'] = 'pending';

        Hotel::create($data);

        return redirect()->route('mitra.hotels.index')
            ->with('success', 'Hotel berhasil ditambahkan! Lengkapi data hotel untuk meningkatkan visibilitas.');
    }

    public function show(Hotel $hotel)
    {
        $this->authorizeHotel($hotel);
        return view('mitra.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        $this->authorizeHotel($hotel);
        return view('mitra.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $this->authorizeHotel($hotel);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'nullable|string', // Accept city name or ID
            'description' => 'required|string',
            'address' => 'required|string',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'price_per_night_min' => 'nullable|numeric|min:0',
            'price_per_night_max' => 'nullable|numeric|min:0',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'total_rooms' => 'nullable|integer|min:1',
            'thumbnail' => 'nullable|string',
            'facilities' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle city selection - just set to null for now
        if (empty($data['city_id']) || !is_numeric($data['city_id'])) {
            $data['city_id'] = null;
        }

        $data['slug'] = Str::slug($data['name']);
        $hotel->update($data);

        return redirect()->route('mitra.hotels.index')
            ->with('success', 'Hotel berhasil diperbarui.');
    }

    public function destroy(Hotel $hotel)
    {
        $this->authorizeHotel($hotel);
        $hotel->delete();

        return redirect()->route('mitra.hotels.index')
            ->with('success', 'Hotel berhasil dihapus.');
    }

    protected function authorizeHotel(Hotel $hotel): void
    {
        if ($hotel->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke hotel ini.');
        }
    }
}



