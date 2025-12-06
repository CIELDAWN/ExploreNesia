@extends('layouts.mitra')

@section('title', 'Dashboard Mitra')
@section('page-title', 'Dashboard')
@section('page-subtitle', $mitra ? 'Kelola bisnis ' . $mitra->business_name : 'Selamat datang, ' . auth()->user()->name)

@section('content')
@if($mitra)
<div class="space-y-6">
    <!-- Business Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 {{ $mitra->status === 'approved' ? 'border-green-500' : ($mitra->status === 'pending' ? 'border-yellow-500' : 'border-red-500') }}">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-ocean-500 to-ocean-600 flex items-center justify-center">
                    <i class="{{ $mitra->getBusinessTypeIcon() }} text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $mitra->business_name }}</h2>
                    <p class="text-gray-600 capitalize">{{ $mitra->business_type }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $mitra->getStatusBadgeClass() }}">
                            {{ ucfirst($mitra->status) }}
                        </span>
                        @if($mitra->average_rating)
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="text-sm font-medium">{{ number_format($mitra->average_rating, 1) }}</span>
                                <span class="text-sm text-gray-500">({{ $mitra->total_reviews }} ulasan)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-right">
                <a href="{{ route('mitra.edit') }}" class="btn bg-ocean-600 text-white hover:bg-ocean-700">
                    <i class="fas fa-edit mr-2"></i> Edit Bisnis
                </a>
            </div>
        </div>

        @if($mitra->status === 'pending')
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-yellow-600"></i>
                    <p class="text-sm font-medium text-yellow-800">Menunggu Persetujuan</p>
                </div>
                <p class="text-sm text-yellow-700 mt-1">Bisnis Anda sedang dalam proses review oleh admin. Anda akan mendapat notifikasi setelah disetujui.</p>
            </div>
        @elseif($mitra->status === 'rejected')
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-600"></i>
                    <p class="text-sm font-medium text-red-800">Bisnis Ditolak</p>
                </div>
                @if($mitra->rejection_reason)
                    <p class="text-sm text-red-700 mt-1">{{ $mitra->rejection_reason }}</p>
                @endif
                <p class="text-sm text-red-700 mt-1">Silakan edit data bisnis Anda dan submit ulang.</p>
            </div>
        @elseif($mitra->status === 'approved')
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <p class="text-sm font-medium text-green-800">Bisnis Disetujui</p>
                </div>
                <p class="text-sm text-green-700 mt-1">Selamat! Bisnis Anda sudah aktif dan dapat dilihat oleh pengunjung.</p>
            </div>
        @endif
    </div>

    <!-- Business Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-ocean-600"></i>
                Informasi Bisnis
            </h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Deskripsi</label>
                    <p class="text-gray-900">{{ $mitra->business_description ?: 'Belum ada deskripsi' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Alamat</label>
                    <p class="text-gray-900">{{ $mitra->business_address ?: 'Belum ada alamat' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Lokasi</label>
                    <p class="text-gray-900">
                        {{ $mitra->city->name ?? 'Tidak diketahui' }}, 
                        {{ $mitra->province->name ?? 'Tidak diketahui' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-phone text-ocean-600"></i>
                Informasi Kontak
            </h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Telepon</label>
                    <p class="text-gray-900">{{ $mitra->contact_phone ?: 'Belum ada nomor telepon' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-900">{{ $mitra->contact_email ?: 'Belum ada email' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Website</label>
                    @if($mitra->website)
                        <a href="{{ $mitra->website }}" target="_blank" class="text-ocean-600 hover:text-ocean-700">
                            {{ $mitra->website }}
                        </a>
                    @else
                        <p class="text-gray-900">Belum ada website</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    </div>
@else
<!-- No Business Data -->
<div class="bg-white rounded-xl shadow-sm p-8">
    <div class="text-center">
        <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-orange-600 text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Data Bisnis Belum Lengkap</h2>
        <p class="text-gray-600 mb-6">Anda belum melengkapi data bisnis. Silakan isi informasi bisnis Anda untuk mulai menggunakan platform ExploreNesia.</p>
        
        <a href="{{ route('mitra.create') }}" class="btn bg-ocean-600 text-white hover:bg-ocean-700 text-lg px-8 py-3">
            <i class="fas fa-plus mr-2"></i> Lengkapi Data Bisnis
        </a>
        
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-semibold text-blue-900 mb-2">Yang Perlu Anda Siapkan:</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Nama dan jenis bisnis (Hotel/Restoran/Wisata)</li>
                <li>• Deskripsi lengkap bisnis Anda</li>
                <li>• Alamat dan lokasi bisnis</li>
                <li>• Informasi kontak (telepon, email)</li>
                <li>• Logo bisnis (opsional)</li>
            </ul>
        </div>
    </div>
</div>
@endif
@endsection