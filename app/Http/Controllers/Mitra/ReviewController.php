<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        [$destinationIds, $hotelIds, $restaurantIds] = $this->ownedResourceIds();

        $reviews = Review::with(['user', 'reviewable'])
            ->where(function ($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                $query->when(!empty($destinationIds), function ($q) use ($destinationIds) {
                    $q->orWhere(function ($sub) use ($destinationIds) {
                        $sub->where('reviewable_type', Destination::class)
                            ->whereIn('reviewable_id', $destinationIds);
                    });
                });

                $query->when(!empty($hotelIds), function ($q) use ($hotelIds) {
                    $q->orWhere(function ($sub) use ($hotelIds) {
                        $sub->where('reviewable_type', Hotel::class)
                            ->whereIn('reviewable_id', $hotelIds);
                    });
                });

                $query->when(!empty($restaurantIds), function ($q) use ($restaurantIds) {
                    $q->orWhere(function ($sub) use ($restaurantIds) {
                        $sub->where('reviewable_type', Restaurant::class)
                            ->whereIn('reviewable_id', $restaurantIds);
                    });
                });
            })
            ->when($request->rating, fn ($q, $rating) => $q->where('rating', $rating))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('mitra.reviews.index', compact('reviews'));
    }

    protected function ownedResourceIds(): array
    {
        $userId = auth()->id();

        return [
            Destination::where('user_id', $userId)->pluck('id')->toArray(),
            Hotel::where('user_id', $userId)->pluck('id')->toArray(),
            Restaurant::where('user_id', $userId)->pluck('id')->toArray(),
        ];
    }
}






