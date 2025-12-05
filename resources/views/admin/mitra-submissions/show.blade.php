@extends('layouts.admin')

@section('title', 'Detail Bisnis Mitra')
@section('page-title', 'Detail Bisnis Mitra')
@section('page-subtitle', 'Lihat informasi lengkap bisnis yang diajukan mitra')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-start gap-5 border-l-4 {{ $mitra->getStatusBadgeClass() }}">
        <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-ocean-500 to-ocean-600 flex items-center justify-center text-white text-3xl">
            <i class="{{ $mitra->getBusinessTypeIcon() }}"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $mitra->business_name }}</h2>
                    <p class="text-sm text-gray-600 mb-2">
                        <span class="font-semibold">{{ $mitra->getBusinessTypeLabel() }}</span> •
                        {{ $mitra->city->name ?? 'Kota tidak diketahui' }},
                        {{ optional($mitra->province)->name ?? 'Provinsi tidak diketahui' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Diajukan oleh: <span class="font-semibold">{{ $mitra->user->name }}</span>
                        (<span class="text-gray-600">{{ $mitra->user->email }}</span>)
                    </p>
                </div>
                <div class="text-right space-y-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $mitra->getStatusBadgeClass() }}">
                        {{ ucfirst($mitra->status) }}
                    </span>
                    <p class="text-xs text-gray-500">
                        Diajukan: {{ $mitra->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detail Utama -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <i class="fas fa-info-circle text-ocean-600"></i>
                Informasi Bisnis
            </h3>
            <div class="space-y-4 text-sm text-gray-700">
                <div>
                    <p class="font-semibold text-gray-800 mb-1">Deskripsi</p>
                    <p>{{ $mitra->business_description ?: 'Belum ada deskripsi.' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 mb-1">Alamat</p>
                    <p>{{ $mitra->business_address ?: 'Belum ada alamat.' }}</p>
                </div>
                @if($mitra->business_type === 'wisata')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-semibold text-gray-800 mb-1">Harga Tiket (perkiraan)</p>
                            <p>Rp {{ number_format($mitra->ticket_price ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @elseif($mitra->business_type === 'hotel')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-semibold text-gray-800 mb-1">Harga Kamar Single (mulai)</p>
                            <p>Rp {{ number_format($mitra->room_price_single ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 mb-1">Harga Kamar Double (mulai)</p>
                            <p>Rp {{ number_format($mitra->room_price_double ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @elseif($mitra->business_type === 'restoran')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-semibold text-gray-800 mb-1">Perkiraan Harga per Orang</p>
                            <p>Rp {{ number_format($mitra->reservation_price ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kontak & Thumbnail -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-3 text-sm text-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-phone text-ocean-600"></i>
                    Informasi Kontak
                </h3>
                <p><span class="font-semibold">Telepon:</span> {{ $mitra->contact_phone ?: 'Belum diisi' }}</p>
                <p><span class="font-semibold">Email:</span> {{ $mitra->contact_email ?: 'Belum diisi' }}</p>
                <p>
                    <span class="font-semibold">Website:</span>
                    @if($mitra->website)
                        <a href="{{ $mitra->website }}" target="_blank" class="text-ocean-600 hover:text-ocean-700">{{ $mitra->website }}</a>
                    @else
                        Belum diisi
                    @endif
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-image text-ocean-600"></i>
                    Thumbnail
                </h3>
                @if($mitra->thumbnail)
                    <img src="{{ asset('storage/'.$mitra->thumbnail) }}" alt="Thumbnail {{ $mitra->business_name }}" class="w-full rounded-lg object-cover max-h-64">
                @else
                    <div class="w-full h-40 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-3xl"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mt-2">
        <a href="{{ route('admin.mitra-submissions.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke daftar
        </a>
        <div class="flex items-center gap-2">
            @if($mitra->status === 'pending')
                <form action="{{ route('admin.mitra-submissions.approve') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="mitra">
                    <input type="hidden" name="id" value="{{ $mitra->id }}">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700" onclick="return confirm('Setujui pengajuan ini?')">
                        <i class="fas fa-check mr-2"></i>Setujui
                    </button>
                </form>
                <button onclick="document.getElementById('detailRejectModal').classList.remove('hidden');" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal tolak dari halaman detail -->
<div id="detailRejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Tolak Pengajuan</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $mitra->business_name }}</p>
        </div>
        <form action="{{ route('admin.mitra-submissions.reject') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="type" value="mitra">
            <input type="hidden" name="id" value="{{ $mitra->id }}">
            <div class="mb-4">
                <label for="detail_reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="reason" id="detail_reason" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('detailRejectModal').classList.add('hidden');" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-times mr-2"></i>Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
