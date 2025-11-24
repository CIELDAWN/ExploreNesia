<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Destination;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleDestination(Destination $destination)
    {
        $user = auth()->user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', Destination::class)
            ->where('favoritable_id', $destination->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Destinasi dihapus dari favorit.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_type' => Destination::class,
                'favoritable_id' => $destination->id,
            ]);
            $message = 'Destinasi ditambahkan ke favorit.';
        }

        return back()->with('success', $message);
    }

    public function index()
    {
        $favorites = auth()->user()->favorites()->with('favoritable')->get();
        return view('user.favorites.index', compact('favorites'));
    }

    public function destroy(Favorite $favorite)
    {
        // Ensure user owns this favorite
        if ($favorite->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $favorite->delete();
        return redirect()->route('user.favorites.index')
            ->with('success', 'Favorit berhasil dihapus.');
    }
}
