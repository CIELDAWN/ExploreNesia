<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display user's favorites
     */
    public function index()
    {
        $favorites = auth()->user()->favorites()
            ->with('favoritable')
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
            'favoritable_type' => 'required|string',
            'favoritable_id' => 'required|integer',
        ]);

        // Check if already favorited
        $exists = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', $request->favoritable_type)
            ->where('favoritable_id', $request->favoritable_id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Item sudah ada di favorites Anda.');
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'favoritable_type' => $request->favoritable_type,
            'favoritable_id' => $request->favoritable_id,
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
            'favoritable_type' => 'required|string',
            'favoritable_id' => 'required|integer',
        ]);

        $favorite = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', $request->favoritable_type)
            ->where('favoritable_id', $request->favoritable_id)
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
                'favoritable_type' => $request->favoritable_type,
                'favoritable_id' => $request->favoritable_id,
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Ditambahkan ke favorites'
            ]);
        }
    }
}