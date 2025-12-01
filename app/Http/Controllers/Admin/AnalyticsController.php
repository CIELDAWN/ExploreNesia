<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get real-time analytics data (API endpoint for AJAX)
     */
    public function getRealtimeStats()
    {
        $stats = [
            // Core Metrics
            'total_users' => User::where('role', 'user')->count(),
            'total_mitra' => Mitra::count(),
            'total_bookings' => Booking::count(),
            'total_reviews' => Review::count(),

            // Active Users (last 30 days)
            'active_users' => User::where('role', 'user')
                ->where('updated_at', '>=', now()->subDays(30))
                ->count(),

            // Mitra Status Breakdown
            'mitra_approved' => Mitra::where('status', 'approved')->count(),
            'mitra_pending' => Mitra::where('status', 'pending')->count(),
            'mitra_rejected' => Mitra::where('status', 'rejected')->count(),

            // Booking Status Breakdown
            'bookings_pending' => Booking::where('status', 'pending')->count(),
            'bookings_confirmed' => Booking::where('status', 'confirmed')->count(),
            'bookings_completed' => Booking::where('status', 'completed')->count(),
            'bookings_cancelled' => Booking::where('status', 'cancelled')->count(),

            // Reviews Status
            'reviews_approved' => Review::where('is_approved', true)->count(),
            'reviews_pending' => Review::where('is_approved', false)->count(),

            // Average Rating
            'average_rating' => round(Review::where('is_approved', true)->avg('rating') ?? 0, 1),

            // Today's Stats
            'today_bookings' => Booking::whereDate('created_at', today())->count(),
            'today_reviews' => Review::whereDate('created_at', today())->count(),
            'today_users' => User::whereDate('created_at', today())->count(),

            // This Month Stats
            'month_bookings' => Booking::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'month_revenue' => Booking::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('final_price'),

            // Growth Percentages (compared to last month)
            'user_growth' => $this->calculateGrowth('users', 'user'),
            'booking_growth' => $this->calculateGrowth('bookings'),
            'review_growth' => $this->calculateGrowth('reviews'),

            // Last updated timestamp
            'last_updated' => now()->toIso8601String(),
        ];

        return response()->json($stats);
    }

    /**
     * Get chart data for bookings trend
     */
    public function getBookingTrend(Request $request)
    {
        $period = $request->get('period', 'week');

        $data = match($period) {
            'week' => $this->getWeeklyBookings(),
            'month' => $this->getMonthlyBookings(),
            'year' => $this->getYearlyBookings(),
            default => $this->getWeeklyBookings(),
        };

        return response()->json($data);
    }

    /**
     * Get revenue statistics
     */
    public function getRevenueStats()
    {
        $stats = [
            'total_revenue' => Booking::where('status', 'completed')->sum('final_price'),
            'today_revenue' => Booking::where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('final_price'),
            'month_revenue' => Booking::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('final_price'),
            'year_revenue' => Booking::where('status', 'completed')
                ->whereYear('created_at', now()->year)
                ->sum('final_price'),
            'revenue_by_month' => $this->getRevenueByMonth(),
        ];

        return response()->json($stats);
    }

    /**
     * Get top performing mitra
     */
    public function getTopMitra(Request $request)
    {
        $limit = $request->get('limit', 5);

        $topMitra = Mitra::where('status', 'approved')
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_reviews', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($mitra) {
                return [
                    'id' => $mitra->id,
                    'business_name' => $mitra->business_name,
                    'business_type' => $mitra->business_type,
                    'average_rating' => $mitra->average_rating ?? 0,
                    'total_reviews' => $mitra->total_reviews ?? 0,
                    'thumbnail' => $mitra->thumbnail,
                ];
            });

        return response()->json($topMitra);
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(Request $request)
    {
        $limit = $request->get('limit', 10);

        $bookings = Booking::with(['user', 'bookable'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function($booking) {
                $bookableName = $booking->bookable ? $booking->bookable->name ?? 'Item' : 'Item';
                return [
                    'type' => 'booking',
                    'title' => 'Booking Baru',
                    'description' => ($booking->user ? $booking->user->name : 'User') . ' melakukan booking ' . $bookableName,
                    'time' => $booking->created_at->diffForHumans(),
                    'created_at' => $booking->created_at,
                ];
            });

        $reviews = Review::with(['user', 'destination'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function($review) {
                $userName = $review->user ? $review->user->name : 'User';
                $destinationName = $review->destination ? $review->destination->name : 'destinasi';
                return [
                    'type' => 'review',
                    'title' => 'Review Baru',
                    'description' => $userName . ' memberikan review ' . $review->rating . ' bintang untuk ' . $destinationName,
                    'time' => $review->created_at->diffForHumans(),
                    'created_at' => $review->created_at,
                ];
            });

        $activities = $bookings->merge($reviews)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();

        return response()->json($activities);
    }

    /**
     * Helper: Calculate growth percentage
     */
    private function calculateGrowth($table, $role = null)
    {
        $query = DB::table($table);

        if ($role) {
            $query->where('role', $role);
        }

        $thisMonth = (clone $query)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = (clone $query)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Helper: Get weekly bookings
     */
    private function getWeeklyBookings()
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Booking::whereDate('created_at', $date)->count();
            $days[] = [
                'label' => $date->isoFormat('dd'),
                'value' => $count,
            ];
        }
        return $days;
    }

    /**
     * Helper: Get monthly bookings (last 12 months)
     */
    private function getMonthlyBookings()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Booking::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $months[] = [
                'label' => $date->isoFormat('MMM'),
                'value' => $count,
            ];
        }
        return $months;
    }

    /**
     * Helper: Get yearly bookings
     */
    private function getYearlyBookings()
    {
        $years = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;
            $count = Booking::whereYear('created_at', $year)->count();
            $years[] = [
                'label' => (string)$year,
                'value' => $count,
            ];
        }
        return $years;
    }

    /**
     * Helper: Get revenue by month
     */
    private function getRevenueByMonth()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Booking::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('final_price');
            $months[] = [
                'month' => $date->isoFormat('MMM Y'),
                'revenue' => $revenue,
            ];
        }
        return $months;
    }
}
