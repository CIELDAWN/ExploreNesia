<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Destination;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display user's favorites
     */
    public function index()
    {
        $favorites = auth()->user()->favorites()
            ->with('destination')
            ->latest()
            ->get();

        return view('user.favorites', compact('favorites'));
    }

    /**
     * Add item to favorites
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|integer|exists:destinations,id',
        ]);

        // Check if already favorited
        $exists = Favorite::where('user_id', auth()->id())
            ->where('destination_id', $request->destination_id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Item sudah ada di favorites Anda.');
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'destination_id' => $request->destination_id,
        ]);

        return back()->with('success', 'Berhasil ditambahkan ke favorites!');
    }

    /**
     * Remove item from favorites
     */
    public function destroy($id)
    {
        $favorite = Favorite::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $favorite->delete();

        return back()->with('success', 'Berhasil dihapus dari favorites!');
    }

    /**
     * Toggle favorite (add/remove)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|integer|exists:destinations,id',
        ]);

        $favorite = Favorite::where('user_id', auth()->id())
            ->where('destination_id', $request->destination_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Dihapus dari favorites'
            ]);
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'destination_id' => $request->destination_id,
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Ditambahkan ke favorites'
            ]);
        }
    }
}