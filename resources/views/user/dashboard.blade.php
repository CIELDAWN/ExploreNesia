@extends('layouts.user')

@section('title', 'Dashboard')
{{-- @section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di ExploreNesia') --}}

@section('content')
    <main class="flex-1 p-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-ocean-500 to-ocean-600 rounded-2xl p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-ocean-100">Mari jelajahi keindahan Indonesia dan temukan pengalaman tak terlupakan</p>
                </div>
                <div class="text-6xl opacity-20">
                    <i class="fas fa-compass"></i>
                </div>
            </div>
        </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-hover bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Destinasi Dikunjungi</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="w-12 h-12 bg-ocean-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-ocean-600"></i>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Favorit</p>
                    <p class="text-2xl font-bold text-gray-800">{{ auth()->user()->favorites()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-forest-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heart text-forest-600"></i>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Ulasan</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="w-12 h-12 bg-earth-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-earth-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Activity -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="card-hover bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('user.destinations') }}" class="flex items-center gap-3 p-3 bg-ocean-50 text-ocean-700 rounded-lg hover:bg-ocean-100 transition">
                    <i class="fas fa-search"></i>
                    Cari Destinasi Baru
                </a>
                <a href="{{ route('user.favorites.index') }}" class="flex items-center gap-3 p-3 bg-forest-50 text-forest-700 rounded-lg hover:bg-forest-100 transition">
                    <i class="fas fa-heart"></i>
                    Lihat Favorit Saya
                </a>
                <a href="#" class="flex items-center gap-3 p-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-history"></i>
                    Riwayat Perjalanan
                </a>
            </div>
        </div>

    <!-- Recent Activity -->
    <div class="card-hover bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            <div class="flex items-center gap-3 text-gray-600">
                <div class="w-8 h-8 bg-ocean-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-heart text-ocean-600 text-sm"></i>
                </div>
                <p>Belum ada aktivitas terbaru</p>
            </div>
            <div class="flex items-center gap-3 text-gray-600">
                <div class="w-8 h-8 bg-forest-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-star text-forest-600 text-sm"></i>
                </div>
                <p>Mulai jelajahi destinasi pertama Anda</p>
            </div>
        </div>
    </div>

    <!-- Recent Destinations -->
    <div class="card-hover bg-white rounded-xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800">Destinasi Terbaru</h3>
        <a href="{{ route('user.destinations') }}" class="text-ocean-600 hover:text-ocean-700 font-semibold">
            Lihat Semua →
        </a>
    </div>
    {{-- <div class="card-hover bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">Destinasi Terbaru</h3>
            <a href="{{ route('user.destinations') }}" class="text-ocean-600 hover:text-ocean-700 font-semibold">
                Lihat Semua →
            </a>
        </div> --}}

        {{-- @if($recentDestinations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentDestinations as $destination)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-ocean-300 transition">
                    <div class="flex items-start gap-3">
                        @if($destination->thumbnail)
                            <img src="{{ asset('storage/' . $destination->thumbnail) }}"
                                 alt="{{ $destination->name }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 text-sm mb-1">{{ $destination->name }}</h4>
                            <p class="text-xs text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $destination->city->name ?? 'N/A' }}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-ocean-600 font-bold text-sm">
                                    Rp {{ number_format($destination->entrance_fee, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('user.destinations.show', $destination->slug) }}"
                                   class="text-xs text-ocean-600 hover:text-ocean-700 font-semibold">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-map-marked-alt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Belum ada destinasi tersedia.</p>
            </div>
        @endif --}}
        @if($recentDestinations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($recentDestinations as $destination)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-ocean-300 transition">
                <div class="flex items-start gap-3">
                    @if($destination->thumbnail)
                        <img src="{{ asset('storage/' . $destination->thumbnail) }}"
                             alt="{{ $destination->name }}"
                             class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm mb-1">{{ $destination->name }}</h4>
                        <p class="text-xs text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $destination->city->name ?? 'N/A' }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-ocean-600 font-bold text-sm">
                                Rp {{ number_format($destination->entrance_fee, 0, ',', '.') }}
                            </span>
                            <a href="{{ route('user.destinations.show', $destination->slug) }}"
                               class="text-xs text-ocean-600 hover:text-ocean-700 font-semibold">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-map-marked-alt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Belum ada destinasi tersedia.</p>
            </div>
        @endif
            </div>
        </main>
@endsection
