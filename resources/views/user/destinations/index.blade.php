@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Jelajahi Destinasi Wisata</h1>
        <p class="text-gray-600 text-lg">Temukan tempat wisata menarik di seluruh Indonesia</p>
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

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
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

                    <!-- Tags Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <div class="max-h-64 overflow-y-auto space-y-2 border border-gray-200 rounded-lg p-3">
                            @php
                                $selectedTags = is_array(request('tags')) ? request('tags') : (request('tags') ? [request('tags')] : []);
                            @endphp
                            @foreach($tags as $tag)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                           {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-gray-300 text-ocean-600 focus:ring-ocean-500 cursor-pointer">
                                    <span class="ml-2 text-sm text-gray-700 flex items-center cursor-pointer">
                                        <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $tag->color ?? '#3B82F6' }}"></span>
                                        {{ $tag->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Pilih satu atau lebih tags untuk filter</p>
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
                        <a href="{{ route('user.destinations') }}" class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition duration-200">
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
                        Menampilkan <span class="font-semibold">{{ $destinations->firstItem() ?: 0 }}-{{ $destinations->lastItem() ?: 0 }}</span>
                        dari <span class="font-semibold">{{ $destinations->total() }}</span> destinasi
                    </p>
                </div>
            </div>

            <!-- Destinations Grid -->
            @if($destinations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($destinations as $destination)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300">
                        <!-- Image -->
                        @if($destination->thumbnail)
                            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $destination->thumbnail) }}');"></div>
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
                                    {{ $destination->category->icon ?? '📍' }} {{ $destination->category->name ?? 'Umum' }}
                                </span>
                                @auth
                                <form action="{{ route('user.favorites.toggle') }}" method="POST" class="favorite-form">
                                    @csrf
                                    <input type="hidden" name="favoritable_type" value="App\Models\Destination">
                                    <input type="hidden" name="favoritable_id" value="{{ $destination->id }}">
                                    <button type="submit" class="favorite-btn text-gray-400 hover:text-red-500 transition">
                                        @if(auth()->user()->favorites()->where('favoritable_type', 'App\Models\Destination')->where('favoritable_id', $destination->id)->exists())
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
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $destination->name }}</h3>
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt text-sm mr-2 text-ocean-600"></i>
                                <span class="text-sm">{{ $destination->city->name ?? 'N/A' }}</span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ Str::limit(strip_tags($destination->description), 100) }}
                            </p>

                            <!-- Tags -->
                            @if($destination->tags->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($destination->tags->take(3) as $tag)
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full text-white"
                                              style="background-color: {{ $tag->color ?? '#3B82F6' }}">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                    @if($destination->tags->count() > 3)
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-700">
                                            +{{ $destination->tags->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Stats -->
                            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($destination->view_count) }}</span>
                                <span><i class="fas fa-star text-yellow-400 mr-1"></i>4.5</span>
                            </div>

                            <!-- Price & Action -->
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($destination->entrance_fee > 0)
                                    <span class="text-ocean-600 font-bold text-lg">
                                        Rp {{ number_format($destination->entrance_fee, 0, ',', '.') }}
                                    </span>
                                    <span class="text-gray-500 text-sm block">/orang</span>
                                    @else
                                    <span class="text-forest-600 font-bold text-lg">Gratis</span>
                                    @endif
                                </div>
                                <a href="{{ route('user.destinations.show', $destination->slug) }}"
                                   class="inline-block bg-ocean-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-ocean-700 transition">
                                    Detail
                                </a>
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
                    <h3 class="text-xl font-bold text-gray-600 mb-2">Tidak ada destinasi ditemukan</h3>
                    <p class="text-gray-500 mb-6">Coba ubah filter pencarian Anda</p>
                    <a href="{{ route('user.destinations') }}" class="inline-block bg-ocean-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-ocean-700 transition">
                        Tampilkan Semua Destinasi
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