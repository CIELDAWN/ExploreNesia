<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MitraSubmissionController extends Controller
{
    /**
     * Display all mitra submissions (destinations, hotels, restaurants)
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // all, destination, hotel, restaurant
        $status = $request->get('status', 'all'); // all, pending, approved, rejected

        // Initialize collections
        $submissions = collect();

        // Get Destinations
        if ($type == 'all' || $type == 'destination') {
            $destinations = Destination::with(['user', 'city', 'category'])
                ->when($status != 'all', function($query) use ($status) {
                    $query->where('status', $status);
                })
                ->latest()
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'destination';
                    $item->type_label = 'Destinasi Wisata';
                    $item->type_icon = 'fa-map-marked-alt';
                    $item->type_color = 'ocean';
                    return $item;
                });
            $submissions = $submissions->merge($destinations);
        }

        // Get Hotels
        if ($type == 'all' || $type == 'hotel') {
            $hotels = Hotel::with(['user', 'city'])
                ->when($status != 'all', function($query) use ($status) {
                    $query->where('status', $status);
                })
                ->latest()
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'hotel';
                    $item->type_label = 'Hotel';
                    $item->type_icon = 'fa-hotel';
                    $item->type_color = 'forest';
                    return $item;
                });
            $submissions = $submissions->merge($hotels);
        }

        // Get Restaurants
        if ($type == 'all' || $type == 'restaurant') {
            $restaurants = Restaurant::with(['user', 'city'])
                ->when($status != 'all', function($query) use ($status) {
                    $query->where('status', $status);
                })
                ->latest()
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'restaurant';
                    $item->type_label = 'Restoran';
                    $item->type_icon = 'fa-utensils';
                    $item->type_color = 'earth';
                    return $item;
                });
            $submissions = $submissions->merge($restaurants);
        }

        // Sort by created_at descending
        $submissions = $submissions->sortByDesc('created_at');

        // Statistics
        $stats = [
            'total' => Destination::count() + Hotel::count() + Restaurant::count(),
            'pending' => Destination::where('status', 'pending')->count() + 
                        Hotel::where('status', 'pending')->count() + 
                        Restaurant::where('status', 'pending')->count(),
            'approved' => Destination::where('status', 'approved')->count() + 
                         Hotel::where('status', 'approved')->count() + 
                         Restaurant::where('status', 'approved')->count(),
            'rejected' => Destination::where('status', 'rejected')->count() + 
                         Hotel::where('status', 'rejected')->count() + 
                         Restaurant::where('status', 'rejected')->count(),
            'destinations' => Destination::count(),
            'hotels' => Hotel::count(),
            'restaurants' => Restaurant::count(),
        ];

        return view('admin.mitra-submissions.index', compact('submissions', 'stats', 'type', 'status'));
    }

    /**
     * Approve submission
     */
    public function approve(Request $request)
    {
        $type = $request->type; // destination, hotel, restaurant
        $id = $request->id;

        $model = $this->getModel($type);
        $submission = $model::findOrFail($id);
        
        $submission->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return back()->with('success', ucfirst($type) . ' berhasil disetujui!');
    }

    /**
     * Reject submission
     */
    public function reject(Request $request)
    {
        $request->validate([
            'type' => 'required|in:destination,hotel,restaurant',
            'id' => 'required|integer',
            'reason' => 'required|string|min:10',
        ]);

        $model = $this->getModel($request->type);
        $submission = $model::findOrFail($request->id);
        
        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', ucfirst($request->type) . ' berhasil ditolak!');
    }

    /**
     * Delete submission
     */
    public function destroy(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        $model = $this->getModel($type);
        $submission = $model::findOrFail($id);
        $submission->delete();

        return back()->with('success', 'Submission berhasil dihapus!');
    }

    /**
     * Get model class based on type
     */
    private function getModel($type)
    {
        return match($type) {
            'destination' => Destination::class,
            'hotel' => Hotel::class,
            'restaurant' => Restaurant::class,
            default => throw new \Exception('Invalid submission type'),
        };
    }
}