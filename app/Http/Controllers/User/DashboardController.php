<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Booking;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // Get recent approved destinations
        $recentDestinations = Destination::where('status', 'approved')
            ->where('is_active', true)
            ->with(['city', 'category'])
            ->latest()
            ->take(4)
            ->get();

        // Hitung jumlah perjalanan yang sudah completed untuk user ini
        $userId = auth()->id();
        $visitedCount = Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // Hitung jumlah ulasan yang sudah dibuat user ini
        $reviewCount = Review::where('user_id', $userId)->count();

        return view('user.dashboard', compact('recentDestinations', 'visitedCount', 'reviewCount'));
    }

    public function home()
    {
        $user = auth()->user();

        $recentDestinations = Destination::where('status', 'approved')
            ->where('is_active', true)
            ->with(['city', 'category'])
            ->latest()
            ->take(4)
            ->get();

        $userId = $user->id;
        $visitedCount = Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        $reviewCount = Review::where('user_id', $userId)->count();

        $hasLocation = !empty($user->province_id) || !empty($user->city_id);

        $recommendedDestinations = collect();
        $recommendedHotels = collect();
        $recommendedRestaurants = collect();

        if ($hasLocation) {
            $cityId = $user->city_id;
            $provinceId = $user->province_id;

            $recommendedDestinations = Destination::where('status', 'approved')
                ->where('is_active', true)
                ->whereHas('city', function ($q) use ($cityId, $provinceId) {
                    if ($cityId) {
                        $q->where('id', $cityId);
                    }

                    if ($provinceId) {
                        $q->orWhere('province_id', $provinceId);
                    }
                })
                ->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [$cityId ?? 0])
                ->latest()
                ->take(6)
                ->get();

            $recommendedHotels = Hotel::where('status', 'approved')
                ->where('is_active', true)
                ->whereHas('city', function ($q) use ($cityId, $provinceId) {
                    if ($cityId) {
                        $q->where('id', $cityId);
                    }

                    if ($provinceId) {
                        $q->orWhere('province_id', $provinceId);
                    }
                })
                ->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [$cityId ?? 0])
                ->latest()
                ->take(6)
                ->get();

            $recommendedRestaurants = Restaurant::where('status', 'approved')
                ->where('is_active', true)
                ->whereHas('city', function ($q) use ($cityId, $provinceId) {
                    if ($cityId) {
                        $q->where('id', $cityId);
                    }

                    if ($provinceId) {
                        $q->orWhere('province_id', $provinceId);
                    }
                })
                ->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [$cityId ?? 0])
                ->latest()
                ->take(6)
                ->get();
        }

        return view('user.home', compact(
            'recentDestinations',
            'visitedCount',
            'reviewCount',
            'hasLocation',
            'recommendedDestinations',
            'recommendedHotels',
            'recommendedRestaurants'
        ));
    }
}
