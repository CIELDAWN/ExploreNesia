<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\MitraBusiness;
use App\Models\User;
use App\Notifications\NewBookingNotification;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        $ownerId = $this->resolveOwnerId($booking);

        if ($ownerId) {
            $owner = User::find($ownerId);
            if ($owner) {
                $owner->notify(new NewBookingNotification($booking));
            }
        }
    }

    protected function resolveOwnerId(Booking $booking): ?int
    {
        $booking->loadMissing('bookable');

        return match ($booking->bookable_type) {
            Destination::class, Hotel::class, Restaurant::class => optional($booking->bookable)->user_id,
            MitraBusiness::class => optional($booking->bookable)->user_id,
            default => null,
        };
    }
}







