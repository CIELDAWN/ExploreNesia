@extends('layouts.user')

@section('title', 'My Favorites')
@section('page-title', 'Favorites Saya')
@section('page-subtitle', 'Destinasi, Hotel, dan Restoran favorit Anda')

@section('content')

<!-- Alert Messages -->
@if(session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
    <div class="flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-xl"></i>
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('info'))
<div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
    <div class="flex items-center gap-3">
        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
        <p class="text-blue-800">{{ session('info') }}</p>
    </div>
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-ocean-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-ocean-100 flex items-center justify-center">
                <i class="fas fa-map-marked-alt text-ocean-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Destinasi</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $favorites->where('favoritable_type', 'App\Models\Destination')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-forest-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-forest-100 flex items-center justify-center">
                <i class="fas fa-hotel text-forest-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Hotel</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $favorites->where('favoritable_type', 'App\Models\Hotel')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-earth-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-earth-100 flex items-center justify-center">
                <i class="fas fa-utensils text-earth-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Restoran</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $favorites->where('favoritable_type', 'App\Models\Restaurant')->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Favorites List -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-heart text-red-500"></i>
            Semua Favorites
        </h2>
    </div>

    @forelse($favorites as $favorite)
    <div class="p-6 border-b border-gray-100 hover:bg-gray-50 transition">
        <div class="flex items-start gap-4">
            <!-- Icon/Image -->
            <div class="flex-shrink-0">
                @if($favorite->favoritable_type === 'App\Models\Destination')
                    <div class="w-16 h-16 rounded-lg bg-ocean-100 flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-ocean-600 text-2xl"></i>
                    </div>
                @elseif($favorite->favoritable_type === 'App\Models\Hotel')
                    <div class="w-16 h-16 rounded-lg bg-forest-100 flex items-center justify-center">
                        <i class="fas fa-hotel text-forest-600 text-2xl"></i>
                    </div>
                @else
                    <div class="w-16 h-16 rounded-lg bg-earth-100 flex items-center justify-center">
                        <i class="fas fa-utensils text-earth-600 text-2xl"></i>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">
                            {{ $favorite->favoritable->name ?? 'N/A' }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                            {{ $favorite->favoritable->city->name ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <!-- Type Badge -->
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($favorite->favoritable_type === 'App\Models\Destination')
                            bg-ocean-100 text-ocean-700
                        @elseif($favorite->favoritable_type === 'App\Models\Hotel')
                            bg-forest-100 text-forest-700
                        @else
                            bg-earth-100 text-earth-700
                        @endif
                    ">
                        @if($favorite->favoritable_type === 'App\Models\Destination')
                            Destinasi
                        @elseif($favorite->favoritable_type === 'App\Models\Hotel')
                            Hotel
                        @else
                            Restoran
                        @endif
                    </span>
                </div>

                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    {{ Str::limit($favorite->favoritable->description ?? '', 150) }}
                </p>

                <!-- Rating -->
                @if($favorite->favoritable->averageRating())
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($favorite->favoritable->averageRating()))
                                <i class="fas fa-star text-sm"></i>
                            @else
                                <i class="far fa-star text-sm"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600">
                        {{ number_format($favorite->favoritable->averageRating(), 1) }}
                        ({{ $favorite->favoritable->reviews_count ?? 0 }} reviews)
                    </span>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <a href="#" class="text-sm text-ocean-600 hover:text-ocean-700 font-medium">
                        <i class="fas fa-eye mr-1"></i>
                        Lihat Detail
                    </a>
                    
                    <form action="{{ route('user.favorites.destroy', $favorite->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium" onclick="return confirm('Hapus dari favorites?')">
                            <i class="fas fa-trash-alt mr-1"></i>
                            Hapus
                        </button>
                    </form>
                    
                    <span class="text-sm text-gray-400">
                        <i class="far fa-clock mr-1"></i>
                        Ditambahkan {{ $favorite->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="p-12 text-center">
        <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-heart-broken text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Favorites</h3>
        <p class="text-gray-600 mb-6">Mulai jelajahi destinasi dan tambahkan ke favorites Anda!</p>
        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-ocean-600 text-white rounded-lg hover:bg-ocean-700 transition">
            <i class="fas fa-compass mr-2"></i>
            Jelajahi Destinasi
        </a>
    </div>
    @endforelse
</div>

@endsection