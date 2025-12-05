<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function show(string $slug)
    {
        $restaurant = Restaurant::with(['city', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();

        return view('user.restaurants.show', compact('restaurant'));
    }
}




