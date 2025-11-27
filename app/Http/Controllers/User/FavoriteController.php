<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Tampilkan daftar favorit user
     */
    public function index()
    {
        $favorites = Favorite::with(['destination.mitra', 'destination.reviews'])
            ->forUser(Auth::id())
            ->latest()
            ->paginate(12);

        return view('user.favorites.index', compact('favorites'));
    }

    /**
     * Tambahkan destinasi ke favorit
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id'
        ]);

        try {
            $favorite = Favorite::create([
                'user_id' => Auth::id(),
                'destination_id' => $request->destination_id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Destinasi berhasil ditambahkan ke favorit',
                    'data' => $favorite
                ]);
            }

            return redirect()->back()->with('success', 'Destinasi berhasil ditambahkan ke favorit');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destinasi sudah ada di favorit'
                ], 422);
            }

            return redirect()->back()->with('error', 'Destinasi sudah ada di favorit');
        }
    }

    /**
     * Hapus destinasi dari favorit
     */
    public function destroy($id)
    {
        $favorite = Favorite::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $favorite->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Destinasi berhasil dihapus dari favorit'
            ]);
        }

        return redirect()->back()->with('success', 'Destinasi berhasil dihapus dari favorit');
    }

    /**
     * Cek apakah destinasi sudah difavoritkan
     */
    public function check($destinationId)
    {
        $isFavorited = Favorite::where('user_id', Auth::id())
            ->where('destination_id', $destinationId)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited
        ]);
    }

    /**
     * Toggle favorit (tambah/hapus)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id'
        ]);

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('destination_id', $request->destination_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Destinasi dihapus dari favorit';
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'destination_id' => $request->destination_id
            ]);
            $message = 'Destinasi ditambahkan ke favorit';
            $isFavorited = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorited' => $isFavorited
        ]);
    }
}
