<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::with(['user', 'city', 'category'])
            ->latest()
            ->paginate(15);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'entrance_fee' => 'nullable|numeric|min:0',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil dibuat.');
    }

    public function show(Destination $destination)
    {
        $destination->load(['user', 'city', 'category', 'images', 'reviews']);
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'entrance_fee' => 'nullable|numeric|min:0',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil dihapus.');
    }

    public function approve(Destination $destination)
    {
        $destination->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);
        return back()->with('success', 'Destinasi berhasil disetujui.');
    }

    public function reject(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $destination->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);
        return back()->with('success', 'Destinasi berhasil ditolak.');
    }
}

