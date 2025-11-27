<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(protected Booking $booking)
    {
        $this->booking->loadMissing(['user', 'bookable']);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $bookableName = $this->booking->bookable->name ?? 'Konten';

        return [
            'title' => 'Pesanan Baru',
            'message' => "{$this->booking->user->name} memesan {$bookableName}.",
            'meta' => "Kode: {$this->booking->booking_code} • Status: " . ucfirst($this->booking->status),
            'booking_id' => $this->booking->id,
        ];
    }
}






