<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with(['user', 'city'])
            ->latest()
            ->paginate(15);
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.restaurants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'cuisine_types' => 'nullable|array',
            'average_price_min' => 'nullable|numeric|min:0',
            'average_price_max' => 'nullable|numeric|min:0',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        Restaurant::create($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restoran berhasil dibuat.');
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load(['user', 'city', 'images', 'reviews']);
        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'cuisine_types' => 'nullable|array',
            'average_price_min' => 'nullable|numeric|min:0',
            'average_price_max' => 'nullable|numeric|min:0',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restoran berhasil diperbarui.');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restoran berhasil dihapus.');
    }

    public function approve(Restaurant $restaurant)
    {
        $restaurant->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);
        return back()->with('success', 'Restoran berhasil disetujui.');
    }

    public function reject(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $restaurant->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);
        return back()->with('success', 'Restoran berhasil ditolak.');
    }
}

