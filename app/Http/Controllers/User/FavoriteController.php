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
        $data = $this->validatePayload($request);

        // Check if already favorited
        $exists = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', $data['favoritable_type'])
            ->where('favoritable_id', $data['favoritable_id'])
            ->exists();

        if ($exists) {
            return back()->with('info', 'Item sudah ada di favorites Anda.');
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'favoritable_type' => $data['favoritable_type'],
            'favoritable_id' => $data['favoritable_id'],
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
        $data = $this->validatePayload($request);

        $favorite = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', $data['favoritable_type'])
            ->where('favoritable_id', $data['favoritable_id'])
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
                'favoritable_type' => $data['favoritable_type'],
                'favoritable_id' => $data['favoritable_id'],
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Ditambahkan ke favorites'
            ]);
        }
    }

    /**
     * Validasi dan mapping payload favorite menjadi tipe/model yang benar.
     */
    protected function validatePayload(Request $request): array
    {
        $validated = $request->validate([
            'type' => 'required|in:destination,hotel,restaurant',
            'id' => 'required|integer',
        ]);

        switch ($validated['type']) {
            case 'hotel':
                $modelClass = Hotel::class;
                break;
            case 'restaurant':
                $modelClass = Restaurant::class;
                break;
            case 'destination':
            default:
                $modelClass = Destination::class;
                break;
        }

        // Pastikan record ada
        $modelClass::findOrFail($validated['id']);

        return [
            'favoritable_type' => $modelClass,
            'favoritable_id' => $validated['id'],
        ];
    }
}