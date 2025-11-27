<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * List review yang dimiliki mitra
     */
    public function index(Request $request)
    {
        [$destinationIds, $hotelIds, $restaurantIds] = $this->ownedResourceIds();

        $reviews = Review::with(['user', 'reviewable'])
            ->where(function ($query) use ($destinationIds, $hotelIds, $restaurantIds) {

                if (!empty($destinationIds)) {
                    $query->orWhere(function ($q) use ($destinationIds) {
                        $q->where('reviewable_type', Destination::class)
                          ->whereIn('reviewable_id', $destinationIds);
                    });
                }

                if (!empty($hotelIds)) {
                    $query->orWhere(function ($q) use ($hotelIds) {
                        $q->where('reviewable_type', Hotel::class)
                          ->whereIn('reviewable_id', $hotelIds);
                    });
                }

                if (!empty($restaurantIds)) {
                    $query->orWhere(function ($q) use ($restaurantIds) {
                        $q->where('reviewable_type', Restaurant::class)
                          ->whereIn('reviewable_id', $restaurantIds);
                    });
                }
            })
            ->when($request->status === 'pending', fn($q) => $q->where('is_approved', false))
            ->when($request->status === 'approved', fn($q) => $q->where('is_approved', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Statistik seperti HEAD
        $allIds = [
            Destination::class => $destinationIds,
            Hotel::class => $hotelIds,
            Restaurant::class => $restaurantIds
        ];

        $stats = [
            'pending' => $this->countStatus(false, $allIds),
            'approved' => $this->countStatus(true, $allIds),
            'total' => $this->countStatus(null, $allIds),
        ];

        return view('mitra.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Approve satu review
     */
    public function approve($id)
    {
        $review = $this->getReviewForMitra($id);

        $review->update([
            'is_approved'  => true,
            'approved_by'  => Auth::id(),
            'approved_at'  => now()
        ]);

        return back()->with('success', 'Review berhasil disetujui');
    }

    /**
     * Reject satu review
     */
    public function reject($id)
    {
        $review = $this->getReviewForMitra($id);

        $review->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null
        ]);

        return back()->with('success', 'Review berhasil ditolak');
    }

    /**
     * Bulk approve
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        $reviewIds = $request->review_ids;

        $updated = Review::whereIn('id', $reviewIds)
            ->update([
                'is_approved' => true,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

        return back()->with('success', "$updated review berhasil disetujui");
    }

    /**
     * Validasi bahwa review milik resource mitra
     */
    private function getReviewForMitra($reviewId)
    {
        [$destinationIds, $hotelIds, $restaurantIds] = $this->ownedResourceIds();

        return Review::where(function ($query) use ($destinationIds, $hotelIds, $restaurantIds) {

                $query->orWhere(function ($q) use ($destinationIds) {
                    $q->where('reviewable_type', Destination::class)
                      ->whereIn('reviewable_id', $destinationIds);
                });

                $query->orWhere(function ($q) use ($hotelIds) {
                    $q->where('reviewable_type', Hotel::class)
                      ->whereIn('reviewable_id', $hotelIds);
                });

                $query->orWhere(function ($q) use ($restaurantIds) {
                    $q->where('reviewable_type', Restaurant::class)
                      ->whereIn('reviewable_id', $restaurantIds);
                });

            })
            ->findOrFail($reviewId);
    }

    /**
     * Ambil ID resource milik mitra
     */
    protected function ownedResourceIds(): array
    {
        $userId = auth()->id();

        return [
            Destination::where('user_id', $userId)->pluck('id')->toArray(),
            Hotel::where('user_id', $userId)->pluck('id')->toArray(),
            Restaurant::where('user_id', $userId)->pluck('id')->toArray(),
        ];
    }

    /**
     * Hitung jumlah status (pending/approved/total)
     */
    private function countStatus($status, $allIds)
    {
        return Review::where(function ($query) use ($allIds) {
                foreach ($allIds as $type => $ids) {
                    if (!empty($ids)) {
                        $query->orWhere(function ($q) use ($type, $ids) {
                            $q->where('reviewable_type', $type)
                              ->whereIn('reviewable_id', $ids);
                        });
                    }
                }
            })
            ->when(!is_null($status), fn ($q) => $q->where('is_approved', $status))
            ->count();
    }
}
