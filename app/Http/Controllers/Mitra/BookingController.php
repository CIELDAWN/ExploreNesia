<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;
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
            ->whereHasMorph('bookable', [Destination::class, Hotel::class, Restaurant::class], function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(15);

        return view('mitra.bookings.index', compact('bookings'));
    }

    /**
     * Konfirmasi booking dari user.
     */
    public function confirm(Booking $booking)
    {
        $this->authorizeBookingOwner($booking);

        $booking->update([
            'status' => 'confirmed',
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ]);

        return back()->with('success', 'Pemesanan berhasil dikonfirmasi.');
    }

    /**
     * Tolak booking dari user dengan alasan.
     */
    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeBookingOwner($booking);

        $data = $request->validate([
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $booking->update([
            'status' => 'rejected',
            'cancellation_reason' => $data['reason'],
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Pemesanan berhasil ditolak.');
    }

    /**
     * Pastikan booking memang milik bisnis mitra yang sedang login.
     */
    private function authorizeBookingOwner(Booking $booking): void
    {
        $user = Auth::user();

        $isOwner = in_array($booking->bookable_type, [Destination::class, Hotel::class, Restaurant::class])
            && $booking->bookable
            && $booking->bookable->user_id === $user->id;

        abort_unless($isOwner, 403, 'Anda tidak berhak mengelola pemesanan ini.');
    }
}




