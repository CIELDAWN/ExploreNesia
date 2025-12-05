<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bookings = Booking::with('bookable')
            ->where('user_id', $user->id)
            ->latest('visit_date')
            ->latest('created_at')
            ->paginate(10);

        return view('user.trips.index', compact('bookings'));
    }

    public function complete(Booking $booking)
    {
        $userId = Auth::id();

        if ($booking->user_id !== $userId || $booking->status !== 'confirmed') {
            abort(403, 'Anda tidak dapat menyelesaikan pemesanan ini.');
        }

        if ($booking->visit_date->isFuture()) {
            return back()->with('error', 'Perjalanan belum terjadi, tidak dapat ditandai selesai.');
        }

        $booking->update([
            'status' => 'completed',
        ]);

        // Jika destinasi dan belum ada review, kirim flag ke halaman riwayat
        if ($booking->bookable_type === Destination::class) {
            $exists = Review::where('user_id', $userId)
                ->where('destination_id', $booking->bookable_id)
                ->exists();

            if (! $exists) {
                return redirect()
                    ->route('user.trips.index')
                    ->with([
                        'success' => 'Perjalanan ditandai selesai, silakan berikan ulasan.',
                        'openReviewModalDestinationId' => $booking->bookable_id,
                        'openReviewModalDestinationName' => optional($booking->bookable)->name,
                    ]);
            }
        }

        return redirect()
            ->route('user.trips.index')
            ->with('success', 'Perjalanan berhasil ditandai selesai.');
    }
}
