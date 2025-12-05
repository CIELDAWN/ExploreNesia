@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="stats-grid">
    <!-- Total Users -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-ocean-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Pengguna</p>
                <h3 class="text-3xl font-bold text-gray-900" data-stat="total_users">{{ number_format($stats['total_users']) }}</h3>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i> User aktif
                </p>
            </div>
            <div class="w-16 h-16 rounded-full gradient-ocean flex items-center justify-center">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Destinations -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-forest-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Destinasi Wisata</p>
                <h3 class="text-3xl font-bold text-gray-900" data-stat="total_destinations">{{ number_format($stats['total_destinations']) }}</h3>
                @if($stats['pending_destinations'] > 0)
                <p class="text-xs text-orange-600 mt-2">
                    <i class="fas fa-clock mr-1"></i> <span data-stat="pending_destinations">{{ $stats['pending_destinations'] }}</span> menunggu
                </p>
                @endif
            </div>
            <div class="w-16 h-16 rounded-full gradient-forest flex items-center justify-center">
                <i class="fas fa-map-marked-alt text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-earth-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Pemesanan</p>
                <h3 class="text-3xl font-bold text-gray-900" data-stat="total_bookings">{{ number_format($stats['total_bookings']) }}</h3>
                <p class="text-xs text-blue-600 mt-2">
                    <i class="fas fa-calendar-check mr-1"></i> Semua waktu
                </p>
            </div>
            <div class="w-16 h-16 rounded-full gradient-earth flex items-center justify-center">
                <i class="fas fa-ticket-alt text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Reviews -->
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Ulasan</p>
                <h3 class="text-3xl font-bold text-gray-900" data-stat="total_reviews">{{ number_format($stats['total_reviews']) }}</h3>
                <p class="text-xs text-yellow-600 mt-2">
                    <i class="fas fa-star mr-1"></i> Feedback pengguna
                </p>
            </div>
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                <i class="fas fa-star text-white text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-ocean-100 flex items-center justify-center">
                <i class="fas fa-hotel text-ocean-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Hotel</p>
                <p class="text-2xl font-bold text-gray-900" data-stat="total_hotels">{{ $stats['total_hotels'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-forest-100 flex items-center justify-center">
                <i class="fas fa-utensils text-forest-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Restoran</p>
                <p class="text-2xl font-bold text-gray-900" data-stat="total_restaurants">{{ $stats['total_restaurants'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-earth-100 flex items-center justify-center">
                <i class="fas fa-user-tie text-earth-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Mitra</p>
                <p class="text-2xl font-bold text-gray-900" data-stat="total_mitra">{{ $stats['total_mitra'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Category Statistics -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-ocean-600"></i>
            Statistik Kategori Wisata
        </h3>
        <div class="space-y-3">
            @foreach($category_stats as $category)
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700">{{ $category->icon }} {{ $category->name }}</span>
                    <span class="text-sm font-bold text-gray-900">{{ $category->destinations_count }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="gradient-ocean h-2 rounded-full" style="width: {{ $category_stats->max('destinations_count') > 0 ? ($category->destinations_count / $category_stats->max('destinations_count') * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Destinations -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-fire text-orange-500"></i>
            Destinasi Terpopuler
        </h3>
        <div class="space-y-4">
            @foreach($popular_destinations as $dest)
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
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-calendar-check text-forest-600"></i>
                Pemesanan Terbaru
            </h3>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-ocean-600 hover:text-ocean-700 font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3" id="recent-bookings-content">
            @forelse($recent_bookings as $booking)
            <div class="flex items-center gap-4 p-3 border border-gray-100 rounded-lg hover:border-ocean-200 transition">
                <div class="w-10 h-10 rounded-full bg-forest-100 flex items-center justify-center">
                    <i class="fas fa-user text-forest-600"></i>
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
            <a href="{{ route('admin.reviews.index') }}" class="text-sm text-ocean-600 hover:text-ocean-700 font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3" id="recent-reviews-content">
            @forelse($recent_reviews as $review)
            <div class="p-3 border border-gray-100 rounded-lg hover:border-yellow-200 transition">
                <div class="flex items-start gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-yellow-600">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $review->user->name }}</p>
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

@push('scripts')
<script>
// Auto-refresh dashboard data setiap 30 detik
let refreshInterval;

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function updateDashboardStats() {
    fetch('{{ route('admin.dashboard') }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Update all statistics with data attributes
        const statElements = document.querySelectorAll('[data-stat]');
        statElements.forEach(element => {
            const statName = element.getAttribute('data-stat');
            const newElement = doc.querySelector(`[data-stat="${statName}"]`);
            if (newElement && element.textContent !== newElement.textContent) {
                // Add animation effect
                element.classList.add('animate-pulse');
                element.textContent = newElement.textContent;
                setTimeout(() => {
                    element.classList.remove('animate-pulse');
                }, 500);
            }
        });

        // Update recent bookings
        const newBookingsContent = doc.querySelector('#recent-bookings-content');
        const currentBookingsContent = document.querySelector('#recent-bookings-content');
        if (newBookingsContent && currentBookingsContent) {
            if (newBookingsContent.innerHTML !== currentBookingsContent.innerHTML) {
                currentBookingsContent.classList.add('opacity-50');
                setTimeout(() => {
                    currentBookingsContent.innerHTML = newBookingsContent.innerHTML;
                    currentBookingsContent.classList.remove('opacity-50');
                }, 200);
            }
        }

        // Update recent reviews
        const newReviewsContent = doc.querySelector('#recent-reviews-content');
        const currentReviewsContent = document.querySelector('#recent-reviews-content');
        if (newReviewsContent && currentReviewsContent) {
            if (newReviewsContent.innerHTML !== currentReviewsContent.innerHTML) {
                currentReviewsContent.classList.add('opacity-50');
                setTimeout(() => {
                    currentReviewsContent.innerHTML = newReviewsContent.innerHTML;
                    currentReviewsContent.classList.remove('opacity-50');
                }, 200);
            }
        }

        console.log('Dashboard updated:', new Date().toLocaleTimeString('id-ID'));
    })
    .catch(error => {
        console.log('Auto-refresh error:', error);
    });
}

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initial message
    console.log('Auto-refresh aktif - Update setiap 30 detik');

    // Set interval for auto-refresh
    refreshInterval = setInterval(updateDashboardStats, 30000); // 30 seconds

    // Show notification on first load
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-4 right-4 bg-ocean-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-3';
    notification.innerHTML = `
        <i class="fas fa-sync-alt animate-spin"></i>
        <span>Auto-refresh aktif</span>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
});

// Clear interval when leaving page
window.addEventListener('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
@endpush
