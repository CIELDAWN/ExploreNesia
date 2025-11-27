<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        [$destinationIds, $hotelIds, $restaurantIds] = $this->ownedResourceIds();

        $bookings = Booking::with(['user', 'bookable'])
            ->where(function ($query) use ($destinationIds, $hotelIds, $restaurantIds) {
                $query->when(!empty($destinationIds), function ($q) use ($destinationIds) {
                    $q->orWhere(function ($sub) use ($destinationIds) {
                        $sub->where('bookable_type', Destination::class)
                            ->whereIn('bookable_id', $destinationIds);
                    });
                });

                $query->when(!empty($hotelIds), function ($q) use ($hotelIds) {
                    $q->orWhere(function ($sub) use ($hotelIds) {
                        $sub->where('bookable_type', Hotel::class)
                            ->whereIn('bookable_id', $hotelIds);
                    });
                });

                $query->when(!empty($restaurantIds), function ($q) use ($restaurantIds) {
                    $q->orWhere(function ($sub) use ($restaurantIds) {
                        $sub->where('bookable_type', Restaurant::class)
                            ->whereIn('bookable_id', $restaurantIds);
                    });
                });
            })
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('mitra.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'cancellation_reason' => 'nullable|string|required_if:status,cancelled',
        ]);

        $this->authorizeBooking($booking);

        $booking->update([
            'status' => $request->status,
            'cancellation_reason' => $request->status === 'cancelled'
                ? $request->cancellation_reason
                : null,
            'confirmed_at' => $request->status === 'confirmed' ? now() : $booking->confirmed_at,
            'cancelled_at' => $request->status === 'cancelled' ? now() : $booking->cancelled_at,
        ]);

        return back()->with('success', 'Status pemesanan berhasil diperbarui.');
    }

    protected function authorizeBooking(Booking $booking): void
    {
        $booking->loadMissing('bookable');

        $ownerId = match ($booking->bookable_type) {
            Destination::class => optional($booking->bookable)->user_id,
            Hotel::class => optional($booking->bookable)->user_id,
            Restaurant::class => optional($booking->bookable)->user_id,
            default => null,
        };

        if ($ownerId !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }
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

