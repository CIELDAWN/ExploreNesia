<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Tampilkan daftar review yang perlu dimoderasi
     */
    public function index(Request $request)
    {
        // Ambil destinasi milik mitra yang sedang login
        $mitraDestinations = Destination::where('mitra_id', Auth::id())
            ->pluck('id');

        $query = Review::with(['user', 'destination'])
            ->whereIn('destination_id', $mitraDestinations)
            ->latest();

        // Filter berdasarkan status
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->pending();
            } elseif ($request->status === 'approved') {
                $query->approved();
            }
        } else {
            // Default tampilkan yang pending
            $query->pending();
        }

        // Filter berdasarkan destinasi
        if ($request->has('destination_id')) {
            $query->forDestination($request->destination_id);
        }

        $reviews = $query->paginate(15);

        // Ambil daftar destinasi mitra untuk filter
        $destinations = Destination::where('mitra_id', Auth::id())
            ->orderBy('name')
            ->get();

        // Hitung statistik
        $stats = [
            'pending' => Review::pending()
                ->whereIn('destination_id', $mitraDestinations)
                ->count(),
            'approved' => Review::approved()
                ->whereIn('destination_id', $mitraDestinations)
                ->count(),
            'total' => Review::whereIn('destination_id', $mitraDestinations)
                ->count()
        ];

        return view('mitra.reviews.index', compact('reviews', 'destinations', 'stats'));
    }

    /**
     * Approve review
     */
    public function approve($id)
    {
        $review = $this->getReviewForMitra($id);

        $review->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Review berhasil disetujui',
                'data' => $review->load(['user', 'destination'])
            ]);
        }

        return redirect()->back()
            ->with('success', 'Review berhasil disetujui');
    }

    /**
     * Reject/Unapprove review
     */
    public function reject($id)
    {
        $review = $this->getReviewForMitra($id);

        $review->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Review berhasil ditolak',
                'data' => $review->load(['user', 'destination'])
            ]);
        }

        return redirect()->back()
            ->with('success', 'Review berhasil ditolak');
    }

    /**
     * Bulk approve reviews
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        $mitraDestinations = Destination::where('mitra_id', Auth::id())
            ->pluck('id');

        $updated = Review::whereIn('id', $request->review_ids)
            ->whereIn('destination_id', $mitraDestinations)
            ->update([
                'is_approved' => true,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$updated} review berhasil disetujui"
        ]);
    }

    /**
     * Tampilkan detail review
     */
    public function show($id)
    {
        $review = $this->getReviewForMitra($id);

        return view('mitra.reviews.show', compact('review'));
    }

    /**
     * Hapus review (soft moderation)
     */
    public function destroy($id)
    {
        $review = $this->getReviewForMitra($id);

        $review->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus'
            ]);
        }

        return redirect()->route('mitra.reviews.index')
            ->with('success', 'Review berhasil dihapus');
    }

    /**
     * Statistik review per destinasi
     */
    public function statistics()
    {
        $mitraDestinations = Destination::where('mitra_id', Auth::id())
            ->pluck('id');

        $stats = [];
        foreach ($mitraDestinations as $destinationId) {
            $destination = Destination::find($destinationId);
            $reviews = Review::approved()->forDestination($destinationId);

            $stats[] = [
                'destination' => $destination,
                'total_reviews' => $reviews->count(),
                'average_rating' => round($reviews->avg('rating'), 1),
                'rating_distribution' => [
                    5 => $reviews->withRating(5)->count(),
                    4 => $reviews->withRating(4)->count(),
                    3 => $reviews->withRating(3)->count(),
                    2 => $reviews->withRating(2)->count(),
                    1 => $reviews->withRating(1)->count(),
                ]
            ];
        }

        return response()->json($stats);
    }

    /**
     * Helper untuk mendapatkan review milik destinasi mitra
     */
    private function getReviewForMitra($reviewId)
    {
        $mitraDestinations = Destination::where('mitra_id', Auth::id())
            ->pluck('id');

        return Review::with(['user', 'destination'])
            ->whereIn('destination_id', $mitraDestinations)
            ->findOrFail($reviewId);
    }
}
