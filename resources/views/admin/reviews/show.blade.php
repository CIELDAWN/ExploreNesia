@extends('layouts.admin')

@section('title', 'Detail Ulasan')
@section('page-title', 'Detail Ulasan')
@section('page-subtitle', 'Informasi lengkap ulasan pengguna')

@section('content')

<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center gap-2 text-ocean-600 hover:text-ocean-700 font-medium">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ulasan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-6 pb-6 border-b">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-ocean-400 to-ocean-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($review->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $review->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $review->user->email }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if($review->is_approved)
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle"></i> Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-clock"></i> Menunggu Persetujuan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Review Content -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-600 uppercase mb-3">Ulasan</h4>
                    <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $review->comment }}</p>
                </div>

                <!-- Review Images -->
                @if($review->images && count($review->images) > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-600 uppercase mb-3">Foto Ulasan</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($review->images as $image)
                        <div class="relative aspect-square rounded-lg overflow-hidden group cursor-pointer">
                            <img src="{{ Storage::url($image) }}" alt="Review Image" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Metadata -->
                <div class="pt-6 border-t">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Tanggal Dibuat</p>
                            <p class="font-medium text-gray-900">{{ $review->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Terakhir Update</p>
                            <p class="font-medium text-gray-900">{{ $review->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval History -->
            @if($review->is_approved && $review->approver)
            <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                <h4 class="font-bold text-green-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Riwayat Persetujuan
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-green-700">Disetujui oleh:</span>
                        <span class="font-medium text-green-900">{{ $review->approver->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-green-700">Tanggal Persetujuan:</span>
                        <span class="font-medium text-green-900">{{ $review->approved_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Business Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-building text-ocean-600"></i> Informasi Bisnis
                </h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Bisnis</p>
                        <p class="font-medium text-gray-900">{{ $review->business_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jenis Bisnis</p>
                        <div class="flex items-center gap-2">
                            @if($review->business_type == 'wisata')
                                <i class="fas fa-map-marked-alt text-ocean-500"></i>
                                <span class="font-medium text-gray-900">Destinasi Wisata</span>
                            @elseif($review->business_type == 'hotel')
                                <i class="fas fa-hotel text-forest-500"></i>
                                <span class="font-medium text-gray-900">Hotel</span>
                            @else
                                <i class="fas fa-utensils text-earth-500"></i>
                                <span class="font-medium text-gray-900">Restoran</span>
                            @endif
                        </div>
                    </div>
                    @if($review->destination)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">ID Destinasi</p>
                        <p class="font-mono text-sm text-gray-700">#{{ $review->destination_id }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Info -->
            @if($review->booking)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-ticket-alt text-forest-600"></i> Informasi Pemesanan
                </h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Kode Booking</p>
                        <p class="font-mono text-sm font-medium text-gray-900">{{ $review->booking->booking_code }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Kunjungan</p>
                        <p class="font-medium text-gray-900">{{ $review->booking->visit_date ? $review->booking->visit_date->format('d F Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status Booking</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($review->booking->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="font-bold text-gray-900 mb-4">Aksi</h4>
                <div class="space-y-3">
                    @if(!$review->is_approved)
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition flex items-center justify-center gap-2" onclick="return confirm('Setujui ulasan ini?')">
                            <i class="fas fa-check-circle"></i> Setujui Ulasan
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition flex items-center justify-center gap-2" onclick="return confirm('Hapus ulasan ini? Tindakan ini tidak dapat dibatalkan!')">
                            <i class="fas fa-trash"></i> Hapus Ulasan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-ocean-500 to-ocean-700 rounded-xl shadow-sm p-6 text-white">
                <h4 class="font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie"></i> Statistik Cepat
                </h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="opacity-90">Total Ulasan User</span>
                        <span class="font-bold">{{ $review->user->reviews()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-90">Rating Rata-rata</span>
                        <span class="font-bold">{{ round($review->user->reviews()->avg('rating'), 1) }}/5</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-90">Booking Selesai</span>
                        <span class="font-bold">{{ $review->user->bookings()->where('status', 'completed')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
