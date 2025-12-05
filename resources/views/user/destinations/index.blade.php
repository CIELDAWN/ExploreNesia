@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        @php
            $currentType = $businessType ?? 'destination';
        @endphp
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
            @if($currentType === 'hotel')
                Jelajahi Hotel Mitra
            @elseif($currentType === 'restaurant')
                Jelajahi Restoran Mitra
            @elseif($currentType === 'all')
                Jelajahi Semua Bisnis Mitra
            @else
                Jelajahi Destinasi Wisata
            @endif
        </h1>
        <p class="text-gray-600 text-lg">
            @if($currentType === 'hotel')
                Temukan hotel pilihan mitra ExploreNesia
            @elseif($currentType === 'restaurant')
                Temukan restoran pilihan mitra ExploreNesia
            @elseif($currentType === 'all')
                Temukan berbagai destinasi wisata, hotel, dan restoran mitra ExploreNesia
            @else
                Temukan tempat wisata menarik di seluruh Indonesia
            @endif
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-4">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Pencarian</h3>

                <!-- Search Form -->
                <form id="filterForm" method="GET" action="{{ route('user.destinations') }}">
                    <!-- Search Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Destinasi</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama destinasi..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500">
                    </div>

                    <!-- Business Type Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                        <select name="business_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500">
                            <option value="all" {{ $currentType === 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="destination" {{ $currentType === 'destination' ? 'selected' : '' }}>Wisata Mitra</option>
                            <option value="hotel" {{ $currentType === 'hotel' ? 'selected' : '' }}>Hotel Mitra</option>
                            <option value="restaurant" {{ $currentType === 'restaurant' ? 'selected' : '' }}>Restoran Mitra</option>
                        </select>
                    </div>

                    <!-- City Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                        <select name="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Range Harga</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                   placeholder="Min"
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                   placeholder="Max"
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500">
                        </div>
                    </div>

                    <!-- Categories Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <div class="max-h-64 overflow-y-auto space-y-2 border border-gray-200 rounded-lg p-3">
                            @php
                                $selectedCategories = is_array(request('categories')) ? request('categories') : (request('categories') ? [request('categories')] : []);
                            @endphp
                            @foreach($categories as $category)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                           {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-gray-300 text-ocean-600 focus:ring-ocean-500 cursor-pointer">
                                    <span class="ml-2 text-sm text-gray-700 flex items-center cursor-pointer">
                                        <span class="inline-block w-3 h-3 rounded-full mr-2">{{ $category->icon ?? '📍' }}</span>
                                        {{ $category->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Pilih satu atau lebih kategori untuk filter</p>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                        <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populer</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button type="submit" class="w-full bg-ocean-600 text-white py-3 rounded-lg font-semibold hover:bg-ocean-700 transition duration-200">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('user.destinations', array_merge(request()->except(['page']), ['business_type' => $businessType ?? 'destination', 'search' => null, 'category' => null, 'city' => null, 'min_price' => null, 'max_price' => null, 'categories' => null, 'sort' => 'latest'])) }}" class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition duration-200">
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <!-- Results Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <p class="text-gray-600">
                        @php
                            if ($currentType === 'hotel') {
                                $label = 'hotel';
                            } elseif ($currentType === 'restaurant') {
                                $label = 'restoran';
                            } elseif ($currentType === 'all') {
                                $label = 'bisnis';
                            } else {
                                $label = 'destinasi';
                            }
                        @endphp
                        Menampilkan <span class="font-semibold">{{ $destinations->firstItem() ?: 0 }}-{{ $destinations->lastItem() ?: 0 }}</span>
                        dari <span class="font-semibold">{{ $destinations->total() }}</span> {{ $label }}
                    </p>
                </div>
            </div>

            <!-- Destinations Grid -->
            @if($destinations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($destinations as $item)
                    @php
                        // Tentukan tipe bisnis per item. Saat mode "Semua", controller
                        // memberikan properti business_type pada setiap item.
                        $itemType = $item->business_type ?? $currentType;
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300">
                        <!-- Image -->
                        @if($item->thumbnail)
                            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $item->thumbnail) }}');"></div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-ocean-400 to-ocean-600 flex items-center justify-center">
                                <i class="fas fa-map-marked-alt text-white text-4xl"></i>
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Category Badge & Favorite -->
                            <div class="flex justify-between items-start mb-3">
                                <span class="inline-block px-3 py-1 bg-ocean-100 text-ocean-700 text-xs font-semibold rounded-full">
                                    @if($itemType === 'hotel')
                                        <i class="fas fa-hotel mr-1"></i> Hotel Mitra
                                    @elseif($itemType === 'restaurant')
                                        <i class="fas fa-utensils mr-1"></i> Restoran Mitra
                                    @else
                                        {{ optional($item->primaryCategory())->icon ?? ($item->category->icon ?? '📍') }}
                                {{ optional($item->primaryCategory())->name ?? ($item->category->name ?? 'Wisata Mitra') }}
                                    @endif
                                </span>
                                @auth
                                @php
                                    $type = $itemType === 'hotel' ? 'hotel' : ($itemType === 'restaurant' ? 'restaurant' : 'destination');
                                    $modelClass = [
                                        'destination' => App\Models\Destination::class,
                                        'hotel' => App\Models\Hotel::class,
                                        'restaurant' => App\Models\Restaurant::class,
                                    ][$type];
                                    $isFavorited = auth()->user()->favorites()
                                        ->where('favoritable_type', $modelClass)
                                        ->where('favoritable_id', $item->id)
                                        ->exists();
                                @endphp
                                <form action="{{ route('user.favorites.toggle') }}" method="POST" class="favorite-form">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button type="submit" class="favorite-btn text-gray-400 hover:text-red-500 transition">
                                        @if($isFavorited)
                                            <i class="fas fa-heart text-red-500"></i>
                                        @else
                                            <i class="far fa-heart"></i>
                                        @endif
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('login') }}" class="text-gray-400 hover:text-red-500 transition">
                                    <i class="far fa-heart"></i>
                                </a>
                                @endauth
                            </div>

                            <!-- Title & Location -->
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $item->name }}</h3>
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt text-sm mr-2 text-ocean-600"></i>
                                <span class="text-sm">{{ $item->city->name ?? 'N/A' }}</span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ Str::limit(strip_tags($item->description), 100) }}
                            </p>

                            <!-- Categories -->
                            @if($item->categories->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($item->categories->take(3) as $category)
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-ocean-100 text-ocean-700">
                                            {{ $category->icon ?? '📍' }} {{ $category->name }}
                                        </span>
                                    @endforeach
                                    @if($item->categories->count() > 3)
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-700">
                                            +{{ $item->categories->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Stats -->
                            @php
                                // Hitung rating rata-rata berdasarkan tipe item
                                if ($itemType === 'hotel' && method_exists($item, 'averageRating')) {
                                    $rating = $item->averageRating();
                                } elseif ($itemType === 'restaurant' && method_exists($item, 'averageRating')) {
                                    $rating = $item->averageRating();
                                } elseif (method_exists($item, 'averageRating')) {
                                    $rating = $item->averageRating();
                                } else {
                                    $rating = 0;
                                }
                            @endphp
                            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($item->view_count ?? 0) }}</span>
                                <span>
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    {{ number_format($rating, 1) }}
                                </span>
                            </div>

                            <!-- Price & Action -->
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($itemType === 'hotel')
                                        @if($item->price_per_night_min > 0)
                                            <span class="text-ocean-600 font-bold text-lg">
                                                Rp {{ number_format($item->price_per_night_min, 0, ',', '.') }}
                                            </span>
                                            @if($item->price_per_night_max && $item->price_per_night_max > $item->price_per_night_min)
                                                <span class="text-gray-500 text-sm block">- Rp {{ number_format($item->price_per_night_max, 0, ',', '.') }} /malam</span>
                                            @else
                                                <span class="text-gray-500 text-sm block">/malam</span>
                                            @endif
                                        @else
                                            <span class="text-gray-500 text-sm">Harga belum tersedia</span>
                                        @endif
                                    @elseif($itemType === 'restaurant')
                                        @if($item->average_price_min > 0)
                                            <span class="text-ocean-600 font-bold text-lg">
                                                Rp {{ number_format($item->average_price_min, 0, ',', '.') }}
                                            </span>
                                            @if($item->average_price_max && $item->average_price_max > $item->average_price_min)
                                                <span class="text-gray-500 text-sm block">- Rp {{ number_format($item->average_price_max, 0, ',', '.') }} /orang</span>
                                            @else
                                                <span class="text-gray-500 text-sm block">/orang</span>
                                            @endif
                                        @else
                                            <span class="text-gray-500 text-sm">Harga belum tersedia</span>
                                        @endif
                                    @else
                                        @if($item->entrance_fee > 0)
                                            <span class="text-ocean-600 font-bold text-lg">
                                                Rp {{ number_format($item->entrance_fee, 0, ',', '.') }}
                                            </span>
                                            <span class="text-gray-500 text-sm block">/orang</span>
                                        @else
                                            <span class="text-forest-600 font-bold text-lg">Gratis</span>
                                        @endif
                                    @endif
                                </div>
                                @if($itemType === 'hotel')
                                    <a href="{{ route('user.hotels.show', $item->slug) }}"
                                   class="inline-block bg-ocean-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-ocean-700 transition">
                                    Detail
                                    </a>
                                @elseif($itemType === 'restaurant')
                                    <a href="{{ route('user.restaurants.show', $item->slug) }}"
                                   class="inline-block bg-ocean-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-ocean-700 transition">
                                    Detail
                                    </a>
                                @else
                                <a href="{{ route('user.destinations.show', $item->slug) }}"
                                   class="inline-block bg-ocean-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-ocean-700 transition">
                                    Detail
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $destinations->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">Tidak ada hasil ditemukan</h3>
                    <p class="text-gray-500 mb-6">Coba ubah atau reset filter pencarian Anda</p>
                    @php
                        if ($currentType === 'hotel') {
                            $emptyLabel = 'Tampilkan Semua Hotel';
                        } elseif ($currentType === 'restaurant') {
                            $emptyLabel = 'Tampilkan Semua Restoran';
                        } elseif ($currentType === 'all') {
                            $emptyLabel = 'Tampilkan Semua Bisnis';
                        } else {
                            $emptyLabel = 'Tampilkan Semua Destinasi';
                        }
                    @endphp
                    <a href="{{ route('user.destinations', array_merge(request()->except(['page']), ['business_type' => $currentType, 'search' => null, 'category' => null, 'city' => null, 'min_price' => null, 'max_price' => null, 'categories' => null, 'sort' => 'latest'])) }}" class="inline-block bg-ocean-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-ocean-700 transition">
                        {{ $emptyLabel }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form when sort changes
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelects = document.querySelectorAll('select[name="sort"]');
        sortSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });

    // Favorite toggle with AJAX
    document.querySelectorAll('.favorite-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const button = this.querySelector('button');
            const icon = button.querySelector('i');
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-500');
                } else {
                    icon.classList.remove('fas', 'text-red-500');
                    icon.classList.add('far');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>
@endpush