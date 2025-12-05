<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Tampilkan semua ulasan yang terkait dengan bisnis mitra ini.
     */
    public function index()
    {
        $user = Auth::user();

        $reviews = Review::with(['user', 'booking.bookable'])
            ->whereHas('booking', function ($query) use ($user) {
                $query->whereHasMorph('bookable', [Destination::class, Hotel::class, Restaurant::class], function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->paginate(10);

        return view('mitra.reviews.index', compact('reviews'));
    }
}
