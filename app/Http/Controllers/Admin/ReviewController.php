<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'destination', 'booking']);

        // Filter by rating
        if ($request->filled('rating')) {
            $query->withRating($request->rating);
        }

        // Filter by business type
        if ($request->filled('business_type')) {
            $query->where('business_type', $request->business_type);
        }

        // Search by user name or comment
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('comment', 'like', "%{$search}%")
                ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'highest_rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'lowest_rating':
                $query->orderBy('rating', 'asc');
                break;
            default:
                $query->latest();
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Review::count(),
            'avg_rating' => round(Review::avg('rating'), 1),
            'rating_distribution' => [
                5 => Review::withRating(5)->count(),
                4 => Review::withRating(4)->count(),
                3 => Review::withRating(3)->count(),
                2 => Review::withRating(2)->count(),
                1 => Review::withRating(1)->count(),
            ],
            'by_business_type' => [
                'wisata' => Review::where('business_type', 'wisata')->count(),
                'hotel' => Review::where('business_type', 'hotel')->count(),
                'restoran' => Review::where('business_type', 'restoran')->count(),
            ],
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $review->load(['user', 'destination', 'booking', 'approver']);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        try {
            DB::beginTransaction();

            $review->delete();

            // Setelah dihapus, hitung ulang rating mitra berdasarkan semua review yang tersisa
            $this->updateMitraRating($review);

            DB::commit();

            return redirect()->route('admin.reviews.index')->with('success', 'Ulasan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus ulasan: ' . $e->getMessage());
        }
    }

    /**
     * Update mitra rating statistics
     */
    private function updateMitraRating(Review $review)
    {
        if (!$review->destination_id) {
            return;
        }

        // Find mitra based on business type and destination
        $mitra = Mitra::where('business_name', $review->business_name)
                     ->where('business_type', $review->business_type)
                     ->first();

        if ($mitra) {
            // Hitung ulang rating berdasarkan semua review yang terkait (tanpa status approve)
            $allReviews = Review::where('destination_id', $review->destination_id)
                                ->where('business_name', $review->business_name)
                                ->get();

            $totalReviews = $allReviews->count();
            $avgRating = $totalReviews > 0 ? round($allReviews->avg('rating'), 1) : 0;

            $mitra->update([
                'average_rating' => $avgRating,
                'total_reviews' => $totalReviews,
            ]);
        }
    }

    /**
     * Get real-time review statistics (AJAX endpoint)
     */
    public function getRealtimeStats()
    {
        $stats = [
            'total' => Review::count(),
            'approved' => Review::approved()->count(),
            'pending' => Review::pending()->count(),
            'avg_rating' => round(Review::avg('rating'), 1),
            'recent_count' => Review::where('created_at', '>=', now()->subDay())->count(),
        ];

        return response()->json($stats);
    }
}
