<?php

namespace App\Observers;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\MitraBusiness;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReviewNotification;

class ReviewObserver
{
    public function created(Review $review): void
    {
        $ownerId = $this->resolveOwnerId($review);

        if ($ownerId) {
            $owner = User::find($ownerId);
            if ($owner) {
                $owner->notify(new NewReviewNotification($review));
            }
        }
    }

    protected function resolveOwnerId(Review $review): ?int
    {
        $review->loadMissing('reviewable');

        return match ($review->reviewable_type) {
            Destination::class, Hotel::class, Restaurant::class => optional($review->reviewable)->user_id,
            MitraBusiness::class => optional($review->reviewable)->user_id,
            default => null,
        };
    }
}







