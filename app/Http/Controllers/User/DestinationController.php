<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Category;
use App\Models\City;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::with(['city', 'category'])
            ->where('status', 'approved')
            ->where('is_active', true);

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by city
        if ($request->has('city') && $request->city != '') {
            $query->where('city_id', $request->city);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('entrance_fee', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('entrance_fee', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('entrance_fee', 'asc');
                break;
            case 'price_high':
                $query->orderBy('entrance_fee', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $destinations = $query->paginate(12);
        $categories = Category::all();

        // Jika cities table sudah ada data, ambil semua
        $cities = City::all();

        return view('user.destinations.index', compact('destinations', 'categories', 'cities'));
    }

    public function show($slug)
    {
        $destination = Destination::with(['city', 'category'])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $destination->increment('view_count');

        // Check if user has favorited this destination
        $isFavorite = false;
        if (auth()->check()) {
            $isFavorite = auth()->user()->favorites()
                ->where('favoritable_type', Destination::class)
                ->where('favoritable_id', $destination->id)
                ->exists();
        }

        // Get related destinations (same category)
        $relatedDestinations = Destination::with(['city', 'category'])
            ->where('category_id', $destination->category_id)
            ->where('id', '!=', $destination->id)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('user.destinations.show', compact('destination', 'isFavorite', 'relatedDestinations'));
    }
}
