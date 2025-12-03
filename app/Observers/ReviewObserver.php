<?php

namespace App\Observers;

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
        $review->loadMissing('destination');

        return optional($review->destination)->user_id;
    }
}







