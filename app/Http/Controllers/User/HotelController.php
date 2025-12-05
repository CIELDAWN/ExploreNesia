<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function show(string $slug)
    {
        $hotel = Hotel::with(['city', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();

        return view('user.hotels.show', compact('hotel'));
    }
}




