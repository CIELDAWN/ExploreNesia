<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_mitra' => User::where('role', 'mitra')->count(),
            'total_destinations' => Destination::count(),
            'total_hotels' => Hotel::count(),
            'total_restaurants' => Restaurant::count(),
            'total_bookings' => Booking::count(),
            'total_reviews' => Review::count(),
            'pending_destinations' => Destination::where('status', 'pending')->count(),
            'pending_hotels' => Hotel::where('status', 'pending')->count(),
            'pending_restaurants' => Restaurant::where('status', 'pending')->count(),
        ];

        // Recent bookings
        $recent_bookings = Booking::with(['user', 'bookable'])
            ->latest()
            ->take(5)
            ->get();

        // Recent reviews
        $recent_reviews = Review::with(['user', 'destination'])
            ->latest()
            ->take(5)
            ->get();

        // Popular destinations
        $popular_destinations = Destination::withCount('reviews')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        // Category statistics
        $category_stats = Category::withCount('destinations')->get();

        // Monthly booking trend - Simple version
        $booking_trend = collect(); // Empty collection for now

        return view('admin.dashboard', compact(
            'stats',
            'recent_bookings',
            'recent_reviews',
            'popular_destinations',
            'category_stats',
            'booking_trend'
        ));
    }

    public function analytics()
    {
        // Advanced analytics data
        return view('admin.analytics');
    }

    public function reports()
    {
        // Reports page
        return view('admin.reports');
    }
}