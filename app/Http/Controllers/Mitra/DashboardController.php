<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            // Initialize all variables with default values
            $stats = [
                'total_hotels' => 0,
                'approved_hotels' => 0,
                'pending_hotels' => 0,
                'rejected_hotels' => 0,
                'total_restaurants' => 0,
                'approved_restaurants' => 0,
                'pending_restaurants' => 0,
                'rejected_restaurants' => 0,
                'total_bookings' => 0,
                'total_reviews' => 0,
                'average_rating' => 0,
            ];

            $recentBookings = collect();
            $recentReviews = collect();
            $averageRating = 0;
            $bookingTrends = [];
            $popularDestinations = collect();

            // Get statistics for hotels
            $hotelStats = Hotel::where('user_id', $user->id)
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected
                ')
                ->first();

            if ($hotelStats) {
                $stats['total_hotels'] = $hotelStats->total ?? 0;
                $stats['approved_hotels'] = $hotelStats->approved ?? 0;
                $stats['pending_hotels'] = $hotelStats->pending ?? 0;
                $stats['rejected_hotels'] = $hotelStats->rejected ?? 0;
            }

            // Get statistics for restaurants
            $restaurantStats = Restaurant::where('user_id', $user->id)
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected
                ')
                ->first();

            if ($restaurantStats) {
                $stats['total_restaurants'] = $restaurantStats->total ?? 0;
                $stats['approved_restaurants'] = $restaurantStats->approved ?? 0;
                $stats['pending_restaurants'] = $restaurantStats->pending ?? 0;
                $stats['rejected_restaurants'] = $restaurantStats->rejected ?? 0;
            }

            // Get recent bookings for mitra's businesses
            $mitraHotels = Hotel::where('user_id', $user->id)->pluck('id');
            $mitraRestaurants = Restaurant::where('user_id', $user->id)->pluck('id');

            $recentBookings = Booking::with(['user', 'bookable'])
                ->where(function ($query) use ($mitraHotels, $mitraRestaurants) {
                    $query->where(function ($q) use ($mitraHotels) {
                        $q->where('bookable_type', 'App\\Models\\Hotel')
                          ->whereIn('bookable_id', $mitraHotels);
                    })
                    ->orWhere(function ($q) use ($mitraRestaurants) {
                        $q->where('bookable_type', 'App\\Models\\Restaurant')
                          ->whereIn('bookable_id', $mitraRestaurants);
                    });
                })
                ->latest()
                ->limit(5)
                ->get();

            // Get recent reviews for mitra's businesses
            $recentReviews = Review::with(['user', 'reviewable'])
                ->where(function ($query) use ($mitraHotels, $mitraRestaurants) {
                    $query->where(function ($q) use ($mitraHotels) {
                        $q->where('reviewable_type', 'App\\Models\\Hotel')
                          ->whereIn('reviewable_id', $mitraHotels);
                    })
                    ->orWhere(function ($q) use ($mitraRestaurants) {
                        $q->where('reviewable_type', 'App\\Models\\Restaurant')
                          ->whereIn('reviewable_id', $mitraRestaurants);
                    });
                })
                ->latest()
                ->limit(5)
                ->get();

            // Calculate average rating
            $totalReviews = Review::where(function ($query) use ($mitraHotels, $mitraRestaurants) {
                $query->where(function ($q) use ($mitraHotels) {
                    $q->where('reviewable_type', 'App\\Models\\Hotel')
                      ->whereIn('reviewable_id', $mitraHotels);
                })
                ->orWhere(function ($q) use ($mitraRestaurants) {
                    $q->where('reviewable_type', 'App\\Models\\Restaurant')
                      ->whereIn('reviewable_id', $mitraRestaurants);
                });
            })->avg('rating');

            $averageRating = $totalReviews ? round($totalReviews, 1) : 0;

            // Calculate total bookings count
            $totalBookingsCount = Booking::where(function ($query) use ($mitraHotels, $mitraRestaurants) {
                $query->where(function ($q) use ($mitraHotels) {
                    $q->where('bookable_type', 'App\\Models\\Hotel')
                      ->whereIn('bookable_id', $mitraHotels);
                })
                ->orWhere(function ($q) use ($mitraRestaurants) {
                    $q->where('bookable_type', 'App\\Models\\Restaurant')
                      ->whereIn('bookable_id', $mitraRestaurants);
                });
            })->count();

            // Calculate total reviews count
            $totalReviewsCount = Review::where(function ($query) use ($mitraHotels, $mitraRestaurants) {
                $query->where(function ($q) use ($mitraHotels) {
                    $q->where('reviewable_type', 'App\\Models\\Hotel')
                      ->whereIn('reviewable_id', $mitraHotels);
                })
                ->orWhere(function ($q) use ($mitraRestaurants) {
                    $q->where('reviewable_type', 'App\\Models\\Restaurant')
                      ->whereIn('reviewable_id', $mitraRestaurants);
                });
            })->count();

            // Update stats array with calculated values
            $stats['total_bookings'] = $totalBookingsCount;
            $stats['total_reviews'] = $totalReviewsCount;
            $stats['average_rating'] = $averageRating;

            // Get booking trends for the last 7 days
            $bookingTrends = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = Booking::where(function ($query) use ($mitraHotels, $mitraRestaurants) {
                    $query->where(function ($q) use ($mitraHotels) {
                        $q->where('bookable_type', 'App\\Models\\Hotel')
                          ->whereIn('bookable_id', $mitraHotels);
                    })
                    ->orWhere(function ($q) use ($mitraRestaurants) {
                        $q->where('bookable_type', 'App\\Models\\Restaurant')
                          ->whereIn('bookable_id', $mitraRestaurants);
                    });
                })
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->count();

                $bookingTrends[] = [
                    'date' => $date->format('M d'),
                    'count' => $count
                ];
            }

        } catch (\Exception $e) {
            Log::error('Mitra Dashboard Error: ' . $e->getMessage());
            
            // Set default values in case of error
            $stats = [
                'total_hotels' => 0,
                'approved_hotels' => 0,
                'pending_hotels' => 0,
                'rejected_hotels' => 0,
                'total_restaurants' => 0,
                'approved_restaurants' => 0,
                'pending_restaurants' => 0,
                'rejected_restaurants' => 0,
                'total_bookings' => 0,
                'total_reviews' => 0,
                'average_rating' => 0,
            ];
            $recentBookings = collect();
            $recentReviews = collect();
            $averageRating = 0;
            $bookingTrends = [];
        }

        // Rename variables to match what the view expects
        $recent_bookings = $recentBookings;
        $recent_reviews = $recentReviews;

        return view('mitra.dashboard', compact(
            'stats',
            'recent_bookings', 
            'recent_reviews', 
            'averageRating', 
            'bookingTrends'
        ));
    }
}