<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(protected Review $review)
    {
        $this->review->loadMissing(['user', 'reviewable']);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $reviewableName = $this->review->reviewable->name ?? 'Konten Anda';

        return [
            'title' => 'Ulasan Baru',
            'message' => "{$this->review->user->name} memberikan rating {$this->review->rating}★ untuk {$reviewableName}.",
            'meta' => str($this->review->comment)->limit(120),
            'review_id' => $this->review->id,
        ];
    }
}








