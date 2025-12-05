<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Mitra;
use App\Models\Category;
use App\Models\City;
use App\Models\Tag;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Services\MitraBusinessSynchronizer;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan semua bisnis mitra tersinkron ke destinasi hanya ketika belum ada destinasi sama sekali
        if (Destination::count() === 0) {
            Mitra::all()->each(function (Mitra $mitra) {
                MitraBusinessSynchronizer::sync($mitra);
            });
        }

        $businessType = $request->get('business_type', 'destination');

        // Build base query berdasarkan jenis bisnis yang dipilih
        switch ($businessType) {
            case 'hotel':
                $query = Hotel::with(['city', 'tags'])
                    ->where('status', 'approved')
                    ->where('is_active', true);
                break;
            case 'restaurant':
                $query = Restaurant::with(['city', 'tags'])
                    ->where('status', 'approved')
                    ->where('is_active', true);
                break;
            default: // destination / wisata
                $businessType = 'destination';
                $query = Destination::with(['city', 'category', 'tags'])
                    ->where('status', 'approved')
                    ->where('is_active', true);
                break;
        }

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by kategori destinasi (hanya untuk destinasi wisata)
        if ($businessType === 'destination' && $request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by city
        if ($request->has('city') && $request->city != '') {
            $query->where('city_id', $request->city);
        }

        // Filter by price range, field menyesuaikan jenis bisnis
        if ($request->has('min_price') && $request->min_price != '') {
            $min = $request->min_price;
            if ($businessType === 'hotel') {
                $query->where('price_per_night_min', '>=', $min);
            } elseif ($businessType === 'restaurant') {
                $query->where('average_price_min', '>=', $min);
            } else {
                $query->where('entrance_fee', '>=', $min);
            }
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $max = $request->max_price;
            if ($businessType === 'hotel') {
                $query->where('price_per_night_max', '<=', $max);
            } elseif ($businessType === 'restaurant') {
                $query->where('average_price_max', '<=', $max);
            } else {
                $query->where('entrance_fee', '<=', $max);
            }
        }

        // Filter by tags (multiple tags - OR logic: must have at least one selected tag)
        if ($request->has('tags')) {
            $tagIds = is_array($request->tags) ? array_filter($request->tags) : [];
            if (count($tagIds) > 0) {
                $query->whereHas('tags', function($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            }
        }

        // Sorting, field menyesuaikan jenis bisnis
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                if ($businessType === 'hotel') {
                    $query->orderBy('price_per_night_min', 'asc');
                } elseif ($businessType === 'restaurant') {
                    $query->orderBy('average_price_min', 'asc');
                } else {
                    $query->orderBy('entrance_fee', 'asc');
                }
                break;
            case 'price_high':
                if ($businessType === 'hotel') {
                    $query->orderBy('price_per_night_max', 'desc');
                } elseif ($businessType === 'restaurant') {
                    $query->orderBy('average_price_max', 'desc');
                } else {
                    $query->orderBy('entrance_fee', 'desc');
                }
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

        $destinations = $query->paginate(12)->appends($request->query());
        $categories = Category::all();

        // Jika cities table sudah ada data, ambil semua
        $cities = City::all();
        
        // Get all tags for filter
        $tags = Tag::orderBy('name')->get();
        return view('user.destinations.index', compact('destinations', 'categories', 'cities', 'tags', 'businessType'));
    }

    public function show($slug)
    {
        $destination = Destination::with(['city', 'category', 'tags'])
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
                ->where('destination_id', $destination->id)
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
