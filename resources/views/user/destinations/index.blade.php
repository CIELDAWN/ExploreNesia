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
                                @if($category->icon)
                                    <i class="{{ $category->icon }} mr-2"></i>
                                @endif
                                {{ $category->name }}
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
                                {{-- Jika cities table sudah ada kolom 'name' --}}
                                @if(isset($city->name))
                                    <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                {{-- Fallback ke ID jika belum ada nama --}}
                                @else
                                    <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                        Kota {{ $city->id }}
                                    </option>
                                @endif
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

                <!-- Mobile Filter Toggle -->
                <button id="mobileFilterToggle" class="lg:hidden bg-ocean-600 text-white px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
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
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Category Badge -->
                            <div class="flex justify-between items-start mb-3">
                                <span class="inline-block px-3 py-1 bg-ocean-100 text-ocean-700 text-xs font-semibold rounded-full">
                                    {{ $destination->category->name ?? 'Umum' }}
                                </span>
                                <button class="favorite-btn text-gray-400 hover:text-red-500 transition"
                                        data-destination-id="{{ $destination->id }}"
                                        data-is-favorited="false">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>

                            <!-- Title & Location -->
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $destination->name }}</h3>
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                <span class="text-sm">{{ $destination->city->name ?? 'N/A' }}</span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ Str::limit(strip_tags($destination->description), 100) }}
                            </p>

                            <!-- Price & Action -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-ocean-600 font-bold text-lg">
                                        Rp {{ number_format($destination->entrance_fee, 0, ',', '.') }}
                                    </span>
                                    <span class="text-gray-500 text-sm block">/orang</span>
                                </div>
                                <div class="space-x-2">
                                    <a href="{{ route('user.destinations.show', $destination->slug) }}"
                                       class="inline-block bg-ocean-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-ocean-700 transition">
                                        Detail
                                    </a>
                                    <button class="inline-block bg-forest-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-forest-700 transition book-btn"
                                            data-destination-id="{{ $destination->id }}"
                                            data-destination-name="{{ $destination->name }}">
                                        Pesan
                                    </button>
                                </div>
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
                <div class="text-center py-12">
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

<!-- Mobile Filter Modal -->
<div id="mobileFilterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden">
    <div class="fixed right-0 top-0 h-full w-80 bg-white overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Filter</h3>
                <button id="closeMobileFilter" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <!-- Filter form will be moved here by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Mobile Filter Toggle
    document.getElementById('mobileFilterToggle').addEventListener('click', function() {
        document.getElementById('mobileFilterModal').classList.remove('hidden');
        // Move filter form to modal
        const filterForm = document.getElementById('filterForm').cloneNode(true);
        document.querySelector('#mobileFilterModal .p-6').appendChild(filterForm);
    });

    document.getElementById('closeMobileFilter').addEventListener('click', function() {
        document.getElementById('mobileFilterModal').classList.add('hidden');
    });

    // Auto-submit form when some filters change
    document.addEventListener('DOMContentLoaded', function() {
        const autoSubmitElements = document.querySelectorAll('select[name="sort"]');
        autoSubmitElements.forEach(element => {
            element.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    });

    // Favorite functionality
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function() {
            const destinationId = this.dataset.destinationId;
            toggleFavorite(destinationId, this);
        });
    });

    // Booking functionality
    document.querySelectorAll('.book-btn').forEach(button => {
        button.addEventListener('click', function() {
            const destinationId = this.dataset.destinationId;
            const destinationName = this.dataset.destinationName;
            showBookingModal(destinationId, destinationName);
        });
    });

    function toggleFavorite(destinationId, button) {
        // Implement favorite toggle logic here
        console.log('Toggle favorite for destination:', destinationId);
    }

    function showBookingModal(destinationId, destinationName) {
        // Implement booking modal logic here
        alert(`Memesan: ${destinationName}`);
    }
</script>
@endpush
@endsection
