@extends('layouts.admin')

@section('title', 'Analitik')
@section('page-title', 'Analitik')
@section('page-subtitle', 'Monitoring real-time statistik dan performa sistem')

@section('content')
<div class="space-y-6">

    <!-- Header Controls -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-sm text-gray-600">Live Updates</span>
            <span class="text-xs text-gray-400" id="last-update">Memuat data...</span>
        </div>

        <div class="flex gap-3">
            <button onclick="refreshAllData()" class="px-4 py-2 bg-ocean-500 text-white rounded-lg hover:bg-ocean-600 transition-all flex items-center gap-2 shadow-md hover:shadow-lg">
                <i class="fas fa-sync-alt" id="refresh-icon"></i>
                <span>Refresh Data</span>
            </button>

            <button onclick="toggleAutoRefresh()" id="auto-refresh-btn" class="px-4 py-2 bg-white border-2 border-ocean-500 text-ocean-600 rounded-lg hover:bg-ocean-50 transition-all flex items-center gap-2">
                <i class="fas fa-play" id="auto-refresh-icon"></i>
                <span id="auto-refresh-text">Auto Refresh (30s)</span>
            </button>
        </div>
    </div>

    <!-- Main Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Users Card -->
        <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-ocean-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-ocean-500 to-ocean-700 flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="flex items-center gap-2 justify-end" id="user-growth-badge">
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold">+0%</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Pengguna</p>
                <h3 class="text-4xl font-bold text-gray-800" id="total-users">
                    <div class="animate-pulse bg-gray-200 h-10 w-24 rounded"></div>
                </h3>
                <p class="text-xs text-gray-400 mt-2">
                    <span id="active-users">0</span> aktif (30 hari terakhir)
                </p>
            </div>
        </div>

        <!-- Total Mitra Card -->
        <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-store text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="flex items-center gap-2 justify-end">
                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full font-semibold" id="mitra-pending-badge">0 pending</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Mitra</p>
                <h3 class="text-4xl font-bold text-gray-800" id="total-mitra">
                    <div class="animate-pulse bg-gray-200 h-10 w-24 rounded"></div>
                </h3>
                <div class="flex items-center gap-2 mt-2 text-xs">
                    <span class="text-green-600">✓ <span id="mitra-approved">0</span> approved</span>
                    <span class="text-gray-400">•</span>
                    <span class="text-red-600">✗ <span id="mitra-rejected">0</span> rejected</span>
                </div>
            </div>
        </div>

        <!-- Total Bookings Card -->
        <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-forest-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-green-500 rounded-lg flex items-center justify-center text-white shadow-lg">
                {{-- <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white"> --}}
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="flex items-center gap-2 justify-end" id="booking-growth-badge">
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold">+0%</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Pemesanan</p>
                <h3 class="text-4xl font-bold text-gray-800" id="total-bookings">
                    <div class="animate-pulse bg-gray-200 h-10 w-24 rounded"></div>
                </h3>
                <p class="text-xs text-gray-400 mt-2">
                    <span id="today-bookings">0</span> booking hari ini
                </p>
            </div>
        </div>

        <!-- Total Reviews Card -->
        <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-yellow-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="flex items-center gap-2 justify-end">
                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full font-semibold" id="reviews-pending-badge">0 pending</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Ulasan</p>
                <h3 class="text-4xl font-bold text-gray-800" id="total-reviews">
                    <div class="animate-pulse bg-gray-200 h-10 w-24 rounded"></div>
                </h3>
                <div class="flex items-center gap-1 mt-2">
                    <span class="text-2xl font-bold text-yellow-500" id="average-rating">0.0</span>
                    <i class="fas fa-star text-yellow-500"></i>
                    <span class="text-xs text-gray-400 ml-1">rata-rata rating</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Revenue & Today's Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between mb-6">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Bulan Ini</p>
                </div>
            </div>
            <div>
                <p class="text-sm opacity-90 mb-2">Total Pendapatan</p>
                <h3 class="text-4xl font-bold mb-4" id="month-revenue">Rp 0</h3>
                <div class="flex items-center justify-between text-sm">
                    <span id="month-bookings">0 pemesanan</span>
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full backdrop-blur-sm">Completed</span>
                </div>
            </div>
        </div>

        <!-- Today's Activity -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-day text-ocean-500"></i>
                Aktivitas Hari Ini
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Booking Baru</p>
                            <p class="text-xs text-gray-500">Hari ini</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-blue-600" id="today-bookings-count">0</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Review Baru</p>
                            <p class="text-xs text-gray-500">Hari ini</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-yellow-600" id="today-reviews-count">0</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">User Baru</p>
                            <p class="text-xs text-gray-500">Hari ini</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-green-600" id="today-users-count">0</span>
                </div>
            </div>
        </div>

        <!-- Booking Status -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-ocean-500"></i>
                Status Pemesanan
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl border-l-4 border-yellow-500">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Pending</p>
                        <p class="text-xs text-gray-500">Menunggu konfirmasi</p>
                    </div>
                    <span class="text-2xl font-bold text-yellow-600" id="bookings-pending">0</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Dikonfirmasi</p>
                        <p class="text-xs text-gray-500">Sedang berjalan</p>
                    </div>
                    <span class="text-2xl font-bold text-blue-600" id="bookings-confirmed">0</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl border-l-4 border-green-500">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Selesai</p>
                        <p class="text-xs text-gray-500">Completed</p>
                    </div>
                    <span class="text-2xl font-bold text-green-600" id="bookings-completed">0</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Booking Trend Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-line text-ocean-500"></i>
                    Tren Pemesanan
                </h3>
                <select id="trend-period" class="px-4 py-2 border-2 border-gray-200 rounded-lg text-sm font-medium focus:border-ocean-500 focus:outline-none transition-all">
                    <option value="week">7 Hari Terakhir</option>
                    <option value="month">12 Bulan Terakhir</option>
                    <option value="year">5 Tahun Terakhir</option>
                </select>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="bookingTrendChart"></canvas>
            </div>
        </div>

        <!-- Top Mitra -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i>
                Top 5 Mitra Terbaik
            </h3>
            <div id="top-mitra-list" class="space-y-3">
                <!-- Loading skeleton -->
                <div class="animate-pulse space-y-3">
                    <div class="h-20 bg-gray-100 rounded-xl"></div>
                    <div class="h-20 bg-gray-100 rounded-xl"></div>
                    <div class="h-20 bg-gray-100 rounded-xl"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-ocean-500"></i>
                Aktivitas Terbaru
            </h3>
            <button onclick="loadRecentActivities()" class="text-sm text-ocean-600 hover:text-ocean-700 font-medium">
                <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
        </div>
        <div id="recent-activities" class="space-y-3">
            <!-- Loading skeleton -->
            <div class="animate-pulse space-y-3">
                <div class="h-16 bg-gray-100 rounded-xl"></div>
                <div class="h-16 bg-gray-100 rounded-xl"></div>
                <div class="h-16 bg-gray-100 rounded-xl"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let bookingChart = null;
let autoRefreshInterval = null;
let autoRefreshEnabled = true;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAllData();
    startAutoRefresh();

    // Event listener for trend period change
    document.getElementById('trend-period').addEventListener('change', function() {
        loadBookingTrend(this.value);
    });
});

// Load all data
function loadAllData() {
    loadRealtimeStats();
    loadBookingTrend('week');
    loadTopMitra();
    loadRecentActivities();
}

// Refresh all data
function refreshAllData() {
    const icon = document.getElementById('refresh-icon');
    icon.classList.add('fa-spin');

    loadAllData();

    setTimeout(() => {
        icon.classList.remove('fa-spin');
    }, 1000);
}

// Toggle auto refresh
function toggleAutoRefresh() {
    autoRefreshEnabled = !autoRefreshEnabled;
    const btn = document.getElementById('auto-refresh-btn');
    const icon = document.getElementById('auto-refresh-icon');
    const text = document.getElementById('auto-refresh-text');

    if (autoRefreshEnabled) {
        btn.classList.remove('bg-red-50', 'border-red-500', 'text-red-600');
        btn.classList.add('bg-white', 'border-ocean-500', 'text-ocean-600');
        icon.classList.remove('fa-pause');
        icon.classList.add('fa-play');
        text.textContent = 'Auto Refresh (30s)';
        startAutoRefresh();
    } else {
        btn.classList.remove('bg-white', 'border-ocean-500', 'text-ocean-600');
        btn.classList.add('bg-red-50', 'border-red-500', 'text-red-600');
        icon.classList.remove('fa-play');
        icon.classList.add('fa-pause');
        text.textContent = 'Auto Refresh OFF';
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }
}

// Start auto-refresh every 30 seconds
function startAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }

    if (autoRefreshEnabled) {
        autoRefreshInterval = setInterval(() => {
            loadRealtimeStats();
            loadRecentActivities();
        }, 30000); // 30 seconds
    }
}

// Load realtime statistics
async function loadRealtimeStats() {
    try {
        const response = await fetch('{{ route("admin.analytics.realtime-stats") }}');
        const data = await response.json();

        // Update main stats
        document.getElementById('total-users').textContent = data.total_users.toLocaleString('id-ID');
        document.getElementById('total-mitra').textContent = data.total_mitra.toLocaleString('id-ID');
        document.getElementById('total-bookings').textContent = data.total_bookings.toLocaleString('id-ID');
        document.getElementById('total-reviews').textContent = data.total_reviews.toLocaleString('id-ID');

        // Update active users
        document.getElementById('active-users').textContent = data.active_users.toLocaleString('id-ID');

        // Update growth badges
        updateGrowthBadge('user-growth-badge', data.user_growth);
        updateGrowthBadge('booking-growth-badge', data.booking_growth);

        // Update mitra stats
        document.getElementById('mitra-pending-badge').textContent = data.mitra_pending + ' pending';
        document.getElementById('mitra-approved').textContent = data.mitra_approved;
        document.getElementById('mitra-rejected').textContent = data.mitra_rejected;

        // Update reviews
        document.getElementById('reviews-pending-badge').textContent = data.reviews_pending + ' pending';
        document.getElementById('average-rating').textContent = data.average_rating;

        // Update today's stats
        document.getElementById('today-bookings').textContent = data.today_bookings;
        document.getElementById('today-bookings-count').textContent = data.today_bookings;
        document.getElementById('today-reviews-count').textContent = data.today_reviews;
        document.getElementById('today-users-count').textContent = data.today_users;

        // Update booking status
        document.getElementById('bookings-pending').textContent = data.bookings_pending;
        document.getElementById('bookings-confirmed').textContent = data.bookings_confirmed;
        document.getElementById('bookings-completed').textContent = data.bookings_completed;

        // Update revenue
        document.getElementById('month-revenue').textContent = formatCurrency(data.month_revenue);
        document.getElementById('month-bookings').textContent = data.month_bookings + ' pemesanan';

        // Update last update time
        const lastUpdate = new Date(data.last_updated);
        document.getElementById('last-update').textContent = 'Update: ' + lastUpdate.toLocaleTimeString('id-ID');

    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Update growth badge
function updateGrowthBadge(elementId, value) {
    const badge = document.getElementById(elementId);
    const isPositive = value >= 0;
    const className = isPositive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
    const symbol = isPositive ? '+' : '';

    badge.innerHTML = `<span class="text-xs px-2 py-1 ${className} rounded-full font-semibold">${symbol}${value}%</span>`;
}

// Load booking trend chart
async function loadBookingTrend(period = 'week') {
    try {
        const response = await fetch(`{{ route("admin.analytics.booking-trend") }}?period=${period}`);
        const data = await response.json();

        const ctx = document.getElementById('bookingTrendChart').getContext('2d');

        if (bookingChart) {
            bookingChart.destroy();
        }

        bookingChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.label),
                datasets: [{
                    label: 'Jumlah Booking',
                    data: data.map(d => d.value),
                    borderColor: '#00BCD4',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#00BCD4',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading booking trend:', error);
    }
}

// Load top mitra
async function loadTopMitra() {
    try {
        const response = await fetch('{{ route("admin.analytics.top-mitra") }}?limit=5');
        const data = await response.json();

        const container = document.getElementById('top-mitra-list');
        container.innerHTML = '';

        if (data.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 py-8">Belum ada data mitra</p>';
            return;
        }

        data.forEach((mitra, index) => {
            const medals = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
            const bgColors = ['bg-yellow-50', 'bg-gray-50', 'bg-orange-50', 'bg-blue-50', 'bg-purple-50'];

            const html = `
                <div class="flex items-center gap-4 p-4 ${bgColors[index]} rounded-xl hover:shadow-md transition-all">
                    <div class="text-3xl">${medals[index]}</div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 text-lg">${mitra.business_name}</p>
                        <p class="text-sm text-gray-500 capitalize">${mitra.business_type}</p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-1 text-yellow-500 font-bold text-lg">
                            <span>${mitra.average_rating}</span>
                            <i class="fas fa-star text-sm"></i>
                        </div>
                        <p class="text-xs text-gray-500">${mitra.total_reviews} ulasan</p>
                    </div>
                </div>
            `;
            container.innerHTML += html;
        });
    } catch (error) {
        console.error('Error loading top mitra:', error);
        document.getElementById('top-mitra-list').innerHTML =
            '<p class="text-center text-red-500 py-8">Error loading data</p>';
    }
}

// Load recent activities
async function loadRecentActivities() {
    try {
        const response = await fetch('{{ route("admin.analytics.recent-activities") }}?limit=10');
        const data = await response.json();

        const container = document.getElementById('recent-activities');
        container.innerHTML = '';

        if (data.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 py-8">Belum ada aktivitas</p>';
            return;
        }

        data.forEach(activity => {
            const iconClass = activity.type === 'booking' ? 'fa-calendar-check text-blue-600' : 'fa-star text-yellow-600';
            const bgClass = activity.type === 'booking' ? 'bg-blue-50' : 'bg-yellow-50';
            const borderClass = activity.type === 'booking' ? 'border-blue-200' : 'border-yellow-200';

            const html = `
                <div class="flex items-center gap-4 p-4 ${bgClass} border ${borderClass} rounded-xl hover:shadow-md transition-all">
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-white shadow-sm">
                        <i class="fas ${iconClass} text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${activity.title}</p>
                        <p class="text-sm text-gray-600">${activity.description}</p>
                    </div>
                    <span class="text-sm text-gray-500 whitespace-nowrap">${activity.time}</span>
                </div>
            `;
            container.innerHTML += html;
        });
    } catch (error) {
        console.error('Error loading activities:', error);
        document.getElementById('recent-activities').innerHTML =
            '<p class="text-center text-red-500 py-8">Error loading activities</p>';
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
});
</script>
@endpush
