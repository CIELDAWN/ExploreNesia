<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Destination;
use App\Models\Mitra;
use App\Models\Category;
use App\Models\City;
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
                $categoryIds = is_array($mitra->selected_categories) ? $mitra->selected_categories : [];
                MitraBusinessSynchronizer::sync($mitra, $categoryIds);
            });
        }

        $businessType = $request->get('business_type', 'destination');

        // Ketika memilih "Semua", gabungkan destinasi, hotel, dan restoran
        if ($businessType === 'all') {
            $sort = $request->get('sort', 'latest');

            $types = ['destination', 'hotel', 'restaurant'];
            $collection = collect();

            foreach ($types as $type) {
                $query = $this->buildBusinessQuery($type, $request);

                $items = $query->get()->map(function ($item) use ($type) {
                    $item->business_type = $type;

                    // Normalisasi field harga untuk kebutuhan sorting
                    if ($type === 'hotel') {
                        $item->price_min = $item->price_per_night_min;
                        $item->price_max = $item->price_per_night_max;
                    } elseif ($type === 'restaurant') {
                        $item->price_min = $item->average_price_min;
                        $item->price_max = $item->average_price_max;
                    } else {
                        $item->price_min = $item->entrance_fee;
                        $item->price_max = $item->entrance_fee;
                    }

                    return $item;
                });

                $collection = $collection->concat($items);
            }

            // Sorting pada collection gabungan
            switch ($sort) {
                case 'price_low':
                    $collection = $collection->sortBy(function ($item) {
                        return $item->price_min ?? 0;
                    });
                    break;
                case 'price_high':
                    $collection = $collection->sortByDesc(function ($item) {
                        return $item->price_max ?? 0;
                    });
                    break;
                case 'popular':
                    $collection = $collection->sortByDesc(function ($item) {
                        return $item->view_count ?? 0;
                    });
                    break;
                case 'name':
                    $collection = $collection->sortBy(function ($item) {
                        return $item->name;
                    }, SORT_NATURAL | SORT_FLAG_CASE);
                    break;
                default:
                    $collection = $collection->sortByDesc(function ($item) {
                        return $item->created_at;
                    });
            }

            $perPage = 12;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $collection->values()->forPage($currentPage, $perPage);

            $destinations = new LengthAwarePaginator(
                $currentItems,
                $collection->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            // Untuk view, tandai bahwa ini mode "Semua"
            $businessType = 'all';
        } else {
            $query = $this->buildBusinessQuery($businessType, $request);

            $destinations = $query->paginate(12)->appends($request->query());
        }
        $categories = Category::orderBy('name')->get();

        // Jika cities table sudah ada data, ambil semua
        $cities = City::all();
        
        return view('user.destinations.index', compact('destinations', 'categories', 'cities', 'businessType'));
    }

    /**
     * Build base query beserta filter dan sorting untuk jenis bisnis tertentu.
     */
    private function buildBusinessQuery(string $businessType, Request $request)
    {
        switch ($businessType) {
            case 'hotel':
                $query = Hotel::with(['city', 'categories'])
                    ->where('status', 'approved')
                    ->where('is_active', true);
                break;
            case 'restaurant':
                $query = Restaurant::with(['city', 'categories'])
                    ->where('status', 'approved')
                    ->where('is_active', true);
                break;
            default:
                $businessType = 'destination';
                $query = Destination::with(['city', 'category', 'categories'])
                    ->where('status', 'approved')
                    ->where('is_active', true);
                break;
        }

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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

        // Filter by categories (multiple categories - OR logic: must have at least one selected category)
        if ($request->has('categories')) {
            $categoryIds = is_array($request->categories) ? array_filter($request->categories) : [];
            if (count($categoryIds) > 0) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
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

        return $query;
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
