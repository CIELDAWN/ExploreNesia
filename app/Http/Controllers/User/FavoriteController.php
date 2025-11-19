<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Destination;

class FavoriteController extends Controller {

    public function toggle($destinationId) {
        // Logic toggle favorite
    }

    public function index() {
        $favorites = auth()->user()->favorites()->with('favoritable')->get();
        return view('user.favorites.index', compact('favorites'));
    }
}
