@extends('layouts.mitra')

@section('title', 'Dashboard Mitra')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Destinations -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-ocean-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Destinasi Wisata</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_destinations']) }}</h3>
                <p class="text-xs text-gray-600 mt-2">
                    <span class="text-green-600">{{ $stats['approved_destinations'] }} disetujui</span>
                    @if($stats['pending_destinations'] > 0)
                    <span class="text-orange-600">• {{ $stats['pending_destinations'] }} menunggu</span>
                    @endif
                </p>
            </div>
            <div class="w-16 h-16 rounded-full gradient-ocean flex items-center justify-center">
                <i class="fas fa-map-marked-alt text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Hotels -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-ocean-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Hotel</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_hotels']) }}</h3>
                <p class="text-xs text-gray-600 mt-2">
                    <span class="text-green-600">{{ $stats['approved_hotels'] }} disetujui</span>
                    @if($stats['pending_hotels'] > 0)
                    <span class="text-orange-600">• {{ $stats['pending_hotels'] }} menunggu</span>
                    @endif
                </p>
            </div>
            <div class="w-16 h-16 rounded-full gradient-ocean flex items-center justify-center">
                <i class="fas fa-hotel text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Restaurants -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-earth-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Restoran</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_restaurants']) }}</h3>
                <p class="text-xs text-gray-600 mt-2">
                    <span class="text-green-600">{{ $stats['approved_restaurants'] }} disetujui</span>
                    @if($stats['pending_restaurants'] > 0)
                    <span class="text-orange-600">• {{ $stats['pending_restaurants'] }} menunggu</span>
                    @endif
                </p>
            </div>
            <div class="w-16 h-16 rounded-full gradient-earth flex items-center justify-center">
                <i class="fas fa-utensils text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Pemesanan</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_bookings']) }}</h3>
                <p class="text-xs text-blue-600 mt-2">
                    <i class="fas fa-calendar-check mr-1"></i> Semua waktu
                </p>
            </div>
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                <i class="fas fa-ticket-alt text-white text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Ulasan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_reviews'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <i class="fas fa-star-half-alt text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Rating Rata-rata</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }} <span class="text-sm text-gray-500">/ 5.0</span></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Ditolak</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $stats['rejected_destinations'] + $stats['rejected_hotels'] + $stats['rejected_restaurants'] }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Popular Destinations -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-fire text-orange-500"></i>
            Destinasi Terpopuler Saya
        </h3>
        <div class="space-y-4">
            @forelse($popular_destinations as $dest)
            <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-ocean-400 to-ocean-600 flex items-center justify-center text-white font-bold">
                    {{ $loop->iteration }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $dest->name }}</p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-eye mr-1"></i> {{ number_format($dest->view_count) }} views
                        <span class="mx-2">•</span>
                        <i class="fas fa-star text-yellow-400 mr-1"></i> {{ $dest->reviews_count }} reviews
                        <span class="mx-2">•</span>
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $dest->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $dest->status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $dest->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($dest->status) }}
                        </span>
                    </p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">Belum ada destinasi</p>
            @endforelse
        </div>
    </div>

    <!-- Status Overview -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-ocean-600"></i>
            Ringkasan Status
        </h3>
        <div class="space-y-4">
            <!-- Approved -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        Disetujui
                    </span>
                    <span class="text-sm font-bold text-gray-900">
                        {{ $stats['approved_destinations'] + $stats['approved_hotels'] + $stats['approved_restaurants'] }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $total = $stats['total_destinations'] + $stats['total_hotels'] + $stats['total_restaurants'];
                        $approved = $stats['approved_destinations'] + $stats['approved_hotels'] + $stats['approved_restaurants'];
                        $approvedPercent = $total > 0 ? ($approved / $total * 100) : 0;
                    @endphp
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $approvedPercent }}%"></div>
                </div>
            </div>

            <!-- Pending -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <i class="fas fa-clock text-orange-500"></i>
                        Menunggu Persetujuan
                    </span>
                    <span class="text-sm font-bold text-gray-900">
                        {{ $stats['pending_destinations'] + $stats['pending_hotels'] + $stats['pending_restaurants'] }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $pending = $stats['pending_destinations'] + $stats['pending_hotels'] + $stats['pending_restaurants'];
                        $pendingPercent = $total > 0 ? ($pending / $total * 100) : 0;
                    @endphp
                    <div class="bg-orange-500 h-3 rounded-full" style="width: {{ $pendingPercent }}%"></div>
                </div>
            </div>

            <!-- Rejected -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <i class="fas fa-times-circle text-red-500"></i>
                        Ditolak
                    </span>
                    <span class="text-sm font-bold text-gray-900">
                        {{ $stats['rejected_destinations'] + $stats['rejected_hotels'] + $stats['rejected_restaurants'] }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $rejected = $stats['rejected_destinations'] + $stats['rejected_hotels'] + $stats['rejected_restaurants'];
                        $rejectedPercent = $total > 0 ? ($rejected / $total * 100) : 0;
                    @endphp
                    <div class="bg-red-500 h-3 rounded-full" style="width: {{ $rejectedPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-calendar-check text-ocean-600"></i>
                Pemesanan Terbaru
            </h3>
            <a href="{{ route('mitra.bookings.index') }}" class="text-sm text-ocean-600 hover:text-ocean-700 font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            @forelse($recent_bookings as $booking)
            <div class="flex items-center gap-4 p-3 border border-gray-100 rounded-lg hover:border-ocean-200 transition">
                <div class="w-10 h-10 rounded-full bg-ocean-100 flex items-center justify-center">
                    <i class="fas fa-user text-ocean-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $booking->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->bookable->name ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-gray-900">{{ $booking->booking_code }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">Belum ada pemesanan</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-comments text-yellow-500"></i>
                Ulasan Terbaru
            </h3>
            <a href="{{ route('mitra.reviews.index') }}" class="text-sm text-ocean-600 hover:text-ocean-700 font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            @forelse($recent_reviews as $review)
            <div class="p-3 border border-gray-100 rounded-lg hover:border-yellow-200 transition">
                <div class="flex items-start gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-yellow-600">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $review->user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $review->reviewable->name ?? 'N/A' }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 line-clamp-2">{{ $review->comment }}</p>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">Belum ada ulasan</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

