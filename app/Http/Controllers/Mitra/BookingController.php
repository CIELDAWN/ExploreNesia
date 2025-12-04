<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Tampilkan semua pemesanan yang terkait dengan bisnis mitra.
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = Booking::with(['user', 'bookable'])
            ->whereHasMorph('bookable', [Destination::class], function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(15);

        return view('mitra.bookings.index', compact('bookings'));
    }
}


