<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Simpan pemesanan untuk sebuah destinasi.
     */
    public function store(Request $request, string $slug)
    {
        $destination = Destination::where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $unitPrice   = $destination->entrance_fee ?? 0;
        $quantity    = $validated['quantity'];
        $totalPrice  = $unitPrice * $quantity;
        $discount    = 0;
        $finalPrice  = $totalPrice - $discount;

        Booking::create([
            'user_id'        => Auth::id(),
            'bookable_type'  => Destination::class,
            'bookable_id'    => $destination->id,
            'booking_date'   => now()->toDateString(),
            'visit_date'     => $validated['visit_date'],
            'quantity'       => $quantity,
            'total_price'    => $totalPrice,
            'discount_amount'=> $discount,
            'final_price'    => $finalPrice,
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('user.destinations.show', $destination->slug)
            ->with('success', 'Pemesanan berhasil dibuat! Mitra akan segera memproses pesanan Anda.');
    }
}


