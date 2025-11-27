<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Tampilkan daftar ulasan user
     */
    public function index(Request $request)
    {
        $query = Review::with(['destination.mitra'])
            ->forUser(Auth::id())
            ->latest();

        // Filter berdasarkan status approval
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        $reviews = $query->paginate(10);

        return view('user.reviews.index', compact('reviews'));
    }

    /**
     * Form buat ulasan baru
     */
    public function create(Request $request)
    {
        $destinationId = $request->get('destination_id');
        $destination = null;

        if ($destinationId) {
            $destination = Destination::findOrFail($destinationId);

            // Cek apakah user sudah pernah review destinasi ini
            $existingReview = Review::where('user_id', Auth::id())
                ->where('destination_id', $destinationId)
                ->first();

            if ($existingReview) {
                return redirect()->route('user.reviews.edit', $existingReview->id)
                    ->with('info', 'Anda sudah pernah memberikan ulasan untuk destinasi ini. Silakan edit ulasan Anda.');
            }
        }

        $destinations = Destination::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user.reviews.create', compact('destinations', 'destination'));
    }

    /**
     * Simpan ulasan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Cek apakah user sudah pernah review destinasi ini
        $existingReview = Review::where('user_id', Auth::id())
            ->where('destination_id', $validated['destination_id'])
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Anda sudah pernah memberikan ulasan untuk destinasi ini');
        }

        // Upload gambar jika ada
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $imagePaths[] = $path;
            }
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'destination_id' => $validated['destination_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'images' => $imagePaths,
            'is_approved' => false
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil dikirim dan menunggu persetujuan mitra',
                'data' => $review->load('destination')
            ]);
        }

        return redirect()->route('user.reviews.index')
            ->with('success', 'Ulasan berhasil dikirim dan menunggu persetujuan mitra');
    }

    /**
     * Form edit ulasan
     */
    public function edit($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $destinations = Destination::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user.reviews.edit', compact('review', 'destinations'));
    }

    /**
     * Update ulasan
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_images' => 'nullable|array'
        ]);

        // Hapus gambar yang dipilih untuk dihapus
        $currentImages = $review->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $currentImages = array_diff($currentImages, [$imagePath]);
            }
        }

        // Upload gambar baru jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $currentImages[] = $path;
            }
        }

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'images' => array_values($currentImages),
            'is_approved' => false, // Reset approval status
            'approved_by' => null,
            'approved_at' => null
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil diperbarui dan menunggu persetujuan kembali',
                'data' => $review->load('destination')
            ]);
        }

        return redirect()->route('user.reviews.index')
            ->with('success', 'Ulasan berhasil diperbarui dan menunggu persetujuan kembali');
    }

    /**
     * Hapus ulasan
     */
    public function destroy($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hapus gambar dari storage
        if ($review->images) {
            foreach ($review->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $review->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil dihapus'
            ]);
        }

        return redirect()->route('user.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus');
    }

    /**
     * Tampilkan detail ulasan
     */
    public function show($id)
    {
        $review = Review::with(['destination.mitra', 'approver'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.reviews.show', compact('review'));
    }
}
