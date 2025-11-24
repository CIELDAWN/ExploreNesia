<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Booking;
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
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            // Statistics for mitra's own businesses
            $stats = [
                'total_destinations' => Destination::where('user_id', $user->id)->count(),
                'total_hotels' => Hotel::where('user_id', $user->id)->count(),
                'total_restaurants' => Restaurant::where('user_id', $user->id)->count(),
                'approved_destinations' => Destination::where('user_id', $user->id)->where('status', 'approved')->count(),
                'approved_hotels' => Hotel::where('user_id', $user->id)->where('status', 'approved')->count(),
                'approved_restaurants' => Restaurant::where('user_id', $user->id)->where('status', 'approved')->count(),
                'pending_destinations' => Destination::where('user_id', $user->id)->where('status', 'pending')->count(),
                'pending_hotels' => Hotel::where('user_id', $user->id)->where('status', 'pending')->count(),
                'pending_restaurants' => Restaurant::where('user_id', $user->id)->where('status', 'pending')->count(),
                'rejected_destinations' => Destination::where('user_id', $user->id)->where('status', 'rejected')->count(),
                'rejected_hotels' => Hotel::where('user_id', $user->id)->where('status', 'rejected')->count(),
                'rejected_restaurants' => Restaurant::where('user_id', $user->id)->where('status', 'rejected')->count(),
            ];

            // Get all mitra's business IDs for bookings and reviews
            $destinationIds = Destination::where('user_id', $user->id)->pluck('id')->toArray();
            $hotelIds = Hotel::where('user_id', $user->id)->pluck('id')->toArray();
            $restaurantIds = Restaurant::where('user_id', $user->id)->pluck('id')->toArray();

            // Recent bookings for mitra's businesses
            $recent_bookings = Booking::where(function($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                if (!empty($destinationIds)) {
                $query->where(function($q) use ($destinationIds) {
                    $q->where('bookable_type', Destination::class)
                      ->whereIn('bookable_id', $destinationIds);
                });
                }
                if (!empty($hotelIds)) {
                    $query->orWhere(function($q) use ($hotelIds) {
                        $q->where('bookable_type', Hotel::class)
                          ->whereIn('bookable_id', $hotelIds);
                    });
                }
                if (!empty($restaurantIds)) {
                    $query->orWhere(function($q) use ($restaurantIds) {
                        $q->where('bookable_type', Restaurant::class)
                          ->whereIn('bookable_id', $restaurantIds);
                    });
                }
                // If all are empty, add a condition that will never match
                if (empty($destinationIds) && empty($hotelIds) && empty($restaurantIds)) {
                    $query->whereRaw('1 = 0');
                }
            })
            ->with(['user', 'bookable'])
            ->latest()
            ->take(5)
            ->get();

            // Total bookings count
            $stats['total_bookings'] = Booking::where(function($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                if (!empty($destinationIds)) {
                $query->where(function($q) use ($destinationIds) {
                    $q->where('bookable_type', Destination::class)
                      ->whereIn('bookable_id', $destinationIds);
                });
                }
                if (!empty($hotelIds)) {
                    $query->orWhere(function($q) use ($hotelIds) {
                        $q->where('bookable_type', Hotel::class)
                          ->whereIn('bookable_id', $hotelIds);
                    });
                }
                if (!empty($restaurantIds)) {
                    $query->orWhere(function($q) use ($restaurantIds) {
                        $q->where('bookable_type', Restaurant::class)
                          ->whereIn('bookable_id', $restaurantIds);
                    });
                }
                // If all are empty, add a condition that will never match
                if (empty($destinationIds) && empty($hotelIds) && empty($restaurantIds)) {
                    $query->whereRaw('1 = 0');
                }
            })->count();

            // Recent reviews for mitra's businesses
            $recent_reviews = Review::where(function($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                if (!empty($destinationIds)) {
                $query->where(function($q) use ($destinationIds) {
                    $q->where('reviewable_type', Destination::class)
                      ->whereIn('reviewable_id', $destinationIds);
                });
                }
                if (!empty($hotelIds)) {
                    $query->orWhere(function($q) use ($hotelIds) {
                        $q->where('reviewable_type', Hotel::class)
                          ->whereIn('reviewable_id', $hotelIds);
                    });
                }
                if (!empty($restaurantIds)) {
                    $query->orWhere(function($q) use ($restaurantIds) {
                        $q->where('reviewable_type', Restaurant::class)
                          ->whereIn('reviewable_id', $restaurantIds);
                    });
                }
                // If all are empty, add a condition that will never match
                if (empty($destinationIds) && empty($hotelIds) && empty($restaurantIds)) {
                    $query->whereRaw('1 = 0');
                }
            })
            ->with(['user', 'reviewable'])
            ->latest()
            ->take(5)
            ->get();

            // Total reviews count
            $stats['total_reviews'] = Review::where(function($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                if (!empty($destinationIds)) {
                    $query->where(function($q) use ($destinationIds) {
                        $q->where('reviewable_type', Destination::class)
                          ->whereIn('reviewable_id', $destinationIds);
                    });
                }
                if (!empty($hotelIds)) {
                    $query->orWhere(function($q) use ($hotelIds) {
                        $q->where('reviewable_type', Hotel::class)
                          ->whereIn('reviewable_id', $hotelIds);
                    });
                }
                if (!empty($restaurantIds)) {
                    $query->orWhere(function($q) use ($restaurantIds) {
                        $q->where('reviewable_type', Restaurant::class)
                          ->whereIn('reviewable_id', $restaurantIds);
                    });
                }
                // If all are empty, add a condition that will never match
                if (empty($destinationIds) && empty($hotelIds) && empty($restaurantIds)) {
                    $query->whereRaw('1 = 0');
                }
            })->count();

            // Average rating
            $stats['average_rating'] = Review::where(function($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                if (!empty($destinationIds)) {
                    $query->where(function($q) use ($destinationIds) {
                        $q->where('reviewable_type', Destination::class)
                          ->whereIn('reviewable_id', $destinationIds);
                    });
                }
                if (!empty($hotelIds)) {
                    $query->orWhere(function($q) use ($hotelIds) {
                        $q->where('reviewable_type', Hotel::class)
                          ->whereIn('reviewable_id', $hotelIds);
                    });
                }
                if (!empty($restaurantIds)) {
                    $query->orWhere(function($q) use ($restaurantIds) {
                        $q->where('reviewable_type', Restaurant::class)
                          ->whereIn('reviewable_id', $restaurantIds);
                    });
                }
                // If all are empty, add a condition that will never match
                if (empty($destinationIds) && empty($hotelIds) && empty($restaurantIds)) {
                    $query->whereRaw('1 = 0');
                }
            })->avg('rating') ?? 0;

            // Popular destinations (mitra's own)
            $popular_destinations = Destination::where('user_id', $user->id)
                ->withCount('reviews')
                ->orderBy('view_count', 'desc')
                ->take(5)
                ->get();

            // Monthly booking trend (last 6 months) for mitra's businesses
            // Use database-agnostic approach
            $connection = DB::getDriverName();
            if ($connection === 'pgsql') {
                $monthColumn = DB::raw('DATE_TRUNC(\'month\', created_at) as month');
            } else {
                $monthColumn = DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month');
            }

            $booking_trend = Booking::select(
                    $monthColumn,
                    DB::raw('count(*) as total')
                )
                ->where(function($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                    if (!empty($destinationIds)) {
                        $query->where(function($q) use ($destinationIds) {
                            $q->where('bookable_type', Destination::class)
                              ->whereIn('bookable_id', $destinationIds);
                        });
                    }
                    if (!empty($hotelIds)) {
                        $query->orWhere(function($q) use ($hotelIds) {
                            $q->where('bookable_type', Hotel::class)
                              ->whereIn('bookable_id', $hotelIds);
                        });
                    }
                    if (!empty($restaurantIds)) {
                        $query->orWhere(function($q) use ($restaurantIds) {
                            $q->where('bookable_type', Restaurant::class)
                              ->whereIn('bookable_id', $restaurantIds);
                        });
                    }
                    // If all are empty, add a condition that will never match
                    if (empty($destinationIds) && empty($hotelIds) && empty($restaurantIds)) {
                        $query->whereRaw('1 = 0');
                    }
                })
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Ensure all variables are set
            $recent_bookings = $recent_bookings ?? collect();
            $recent_reviews = $recent_reviews ?? collect();
            $popular_destinations = $popular_destinations ?? collect();
            $booking_trend = $booking_trend ?? collect();

            return view('mitra.dashboard', [
                'stats' => $stats,
                'recent_bookings' => $recent_bookings,
                'recent_reviews' => $recent_reviews,
                'popular_destinations' => $popular_destinations,
                'booking_trend' => $booking_trend
            ]);
        } catch (\Exception $e) {
            // Log the error and return a safe view with empty data
            Log::error('Mitra Dashboard Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'exception' => $e
            ]);

            return view('mitra.dashboard', [
                'stats' => [
                    'total_destinations' => 0,
                    'total_hotels' => 0,
                    'total_restaurants' => 0,
                    'approved_destinations' => 0,
                    'approved_hotels' => 0,
                    'approved_restaurants' => 0,
                    'pending_destinations' => 0,
                    'pending_hotels' => 0,
                    'pending_restaurants' => 0,
                    'rejected_destinations' => 0,
                    'rejected_hotels' => 0,
                    'rejected_restaurants' => 0,
                    'total_bookings' => 0,
                    'total_reviews' => 0,
                    'average_rating' => 0,
                ],
                'recent_bookings' => collect(),
                'recent_reviews' => collect(),
                'popular_destinations' => collect(),
                'booking_trend' => collect()
            ]);
        }
    }
}

