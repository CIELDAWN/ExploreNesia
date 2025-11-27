<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('city')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('mitra.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('mitra.restaurants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'nullable|string', // Accept city name or ID
            'description' => 'required|string',
            'address' => 'required|string',
            'opening_time' => 'nullable|string|max:50',
            'closing_time' => 'nullable|string|max:50',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'average_price_min' => 'nullable|numeric|min:0',
            'average_price_max' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'thumbnail' => 'nullable|string',
            'cuisine_types' => 'nullable|array',
            'facilities' => 'nullable|array',
        ]);

        // Handle city selection - just set to null for now
        if (empty($data['city_id']) || !is_numeric($data['city_id'])) {
            $data['city_id'] = null;
        }

        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['name']);
        $data['status'] = 'pending';

        Restaurant::create($data);

        return redirect()->route('mitra.restaurants.index')
            ->with('success', 'Restoran berhasil ditambahkan! Lengkapi data restoran untuk meningkatkan visibilitas.');
    }

    public function show(Restaurant $restaurant)
    {
        $this->authorizeRestaurant($restaurant);
        return view('mitra.restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant)
    {
        $this->authorizeRestaurant($restaurant);
        return view('mitra.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $this->authorizeRestaurant($restaurant);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'nullable|string', // Accept city name or ID
            'description' => 'required|string',
            'address' => 'required|string',
            'opening_time' => 'nullable|string|max:50',
            'closing_time' => 'nullable|string|max:50',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'average_price_min' => 'nullable|numeric|min:0',
            'average_price_max' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'thumbnail' => 'nullable|string',
            'cuisine_types' => 'nullable|array',
            'facilities' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle city selection - just set to null for now
        if (empty($data['city_id']) || !is_numeric($data['city_id'])) {
            $data['city_id'] = null;
        }

        $data['slug'] = Str::slug($data['name']);
        $restaurant->update($data);

        return redirect()->route('mitra.restaurants.index')
            ->with('success', 'Restoran berhasil diperbarui.');
    }

    public function destroy(Restaurant $restaurant)
    {
        $this->authorizeRestaurant($restaurant);
        $restaurant->delete();

        return redirect()->route('mitra.restaurants.index')
            ->with('success', 'Restoran berhasil dihapus.');
    }

    protected function authorizeRestaurant(Restaurant $restaurant): void
    {
        if ($restaurant->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke restoran ini.');
        }
    }
}



