@extends('layouts.admin')

@section('title', 'Manajemen Ulasan')
@section('page-title', 'Manajemen Ulasan')
@section('page-subtitle', 'Kelola dan moderasi ulasan pengguna')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- Total Reviews -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Ulasan</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Approved Reviews -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Disetujui</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['approved']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Reviews -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Menunggu</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="fas fa-clock text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Average Rating -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Rating Rata-rata</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['avg_rating'] }}/5</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Business Types -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <p class="text-sm font-medium text-gray-600 mb-3">Per Jenis Bisnis</p>
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600"><i class="fas fa-map-marked-alt text-ocean-500 mr-1"></i> Wisata</span>
                <span class="font-semibold">{{ $stats['by_business_type']['wisata'] }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600"><i class="fas fa-hotel text-forest-500 mr-1"></i> Hotel</span>
                <span class="font-semibold">{{ $stats['by_business_type']['hotel'] }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600"><i class="fas fa-utensils text-earth-500 mr-1"></i> Restoran</span>
                <span class="font-semibold">{{ $stats['by_business_type']['restoran'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Rating Distribution -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-8">
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fas fa-chart-bar text-ocean-600"></i>
        Distribusi Rating
    </h3>
    <div class="space-y-3">
        @for($i = 5; $i >= 1; $i--)
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1 w-24">
                @for($j = 1; $j <= $i; $j++)
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                @endfor
                <span class="text-sm font-medium text-gray-700 ml-2">{{ $i }}</span>
            </div>
            <div class="flex-1">
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $maxCount = max($stats['rating_distribution']);
                        $percentage = $maxCount > 0 ? ($stats['rating_distribution'][$i] / $maxCount * 100) : 0;
                    @endphp
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            <span class="text-sm font-bold text-gray-900 w-16 text-right">{{ $stats['rating_distribution'][$i] }}</span>
        </div>
        @endfor
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-1"></i> Cari
            </label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama user, komentar, atau bisnis..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent">
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-filter mr-1"></i> Status
            </label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            </select>
        </div>

        <!-- Rating Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-star mr-1"></i> Rating
            </label>
            <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
            </select>
        </div>

        <!-- Business Type Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-building mr-1"></i> Jenis
            </label>
            <select name="business_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent">
                <option value="">Semua Jenis</option>
                <option value="wisata" {{ request('business_type') == 'wisata' ? 'selected' : '' }}>Wisata</option>
                <option value="hotel" {{ request('business_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                <option value="restoran" {{ request('business_type') == 'restoran' ? 'selected' : '' }}>Restoran</option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="md:col-span-5 flex gap-2">
            <button type="submit" class="px-6 py-2 gradient-ocean text-white rounded-lg hover:shadow-lg transition">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            <a href="{{ route('admin.reviews.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Reviews Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-list text-ocean-600"></i>
                Daftar Ulasan ({{ $reviews->total() }})
            </h3>

            <!-- Sort -->
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex items-center gap-2">
                @foreach(request()->except('sort') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <label class="text-sm text-gray-600">Urutkan:</label>
                <select name="sort" onchange="this.form.submit()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-ocean-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="highest_rating" {{ request('sort') == 'highest_rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="lowest_rating" {{ request('sort') == 'lowest_rating' ? 'selected' : '' }}>Rating Terendah</option>
                </select>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bisnis</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ulasan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-ocean-100 flex items-center justify-center">
                                <span class="font-bold text-ocean-600 text-sm">{{ strtoupper(substr($review->user->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $review->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900 flex items-center gap-2">
                                @if($review->business_type == 'wisata')
                                    <i class="fas fa-map-marked-alt text-ocean-500"></i>
                                @elseif($review->business_type == 'hotel')
                                    <i class="fas fa-hotel text-forest-500"></i>
                                @else
                                    <i class="fas fa-utensils text-earth-500"></i>
                                @endif
                                {{ Str::limit($review->business_name, 25) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ ucfirst($review->business_type) }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="ml-2 text-sm font-semibold text-gray-900">{{ $review->rating }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 line-clamp-2 max-w-md">{{ $review->comment }}</p>
                        @if($review->images && count($review->images) > 0)
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-images mr-1"></i> {{ count($review->images) }} foto
                            </p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($review->is_approved)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle"></i> Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-clock"></i> Menunggu
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600">{{ $review->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.reviews.show', $review) }}" class="p-2 text-ocean-600 hover:bg-ocean-50 rounded-lg transition" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Setujui" onclick="return confirm('Setujui ulasan ini?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus" onclick="return confirm('Hapus ulasan ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <i class="fas fa-inbox text-6xl text-gray-300"></i>
                            <p class="text-gray-500 font-medium">Tidak ada ulasan ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $reviews->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Auto-refresh stats every 30 seconds
setInterval(function() {
    fetch('{{ route('admin.reviews.index') }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Update stats cards only
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newStats = doc.querySelectorAll('.stat-card');
        const currentStats = document.querySelectorAll('.stat-card');

        newStats.forEach((newStat, index) => {
            if (currentStats[index]) {
                currentStats[index].innerHTML = newStat.innerHTML;
            }
        });
    });
}, 30000); // 30 seconds
</script>
@endpush
