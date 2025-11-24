<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with(['user', 'city'])
            ->latest()
            ->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'price_per_night_min' => 'nullable|numeric|min:0',
            'price_per_night_max' => 'nullable|numeric|min:0',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'total_rooms' => 'nullable|integer|min:1',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        Hotel::create($validated);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel berhasil dibuat.');
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['user', 'city', 'images', 'reviews']);
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'price_per_night_min' => 'nullable|numeric|min:0',
            'price_per_night_max' => 'nullable|numeric|min:0',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'total_rooms' => 'nullable|integer|min:1',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel berhasil diperbarui.');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel berhasil dihapus.');
    }

    public function approve(Hotel $hotel)
    {
        $hotel->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);
        return back()->with('success', 'Hotel berhasil disetujui.');
    }

    public function reject(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $hotel->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);
        return back()->with('success', 'Hotel berhasil ditolak.');
    }
}

