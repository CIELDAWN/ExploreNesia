@extends('layouts.mitra')

@section('title', 'Detail Hotel')
@section('page-title', $hotel->name)
@section('page-subtitle', 'Informasi lengkap hotel Anda')

@section('content')
<div class="mb-6">
    <a href="{{ route('mitra.hotels.index') }}" class="inline-flex items-center text-gray-600 hover:text-ocean-600">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Hotel
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Hotel</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kota</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->city->name ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->description }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->address }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Hotel</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rating Bintang</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($hotel->star_rating)
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $hotel->star_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            ({{ $hotel->star_rating }} bintang)
                        @else
                            <span class="text-gray-500">Belum diatur</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Kamar</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->total_rooms ?? 'Belum diatur' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per Malam (Min)</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->price_per_night_min ? 'Rp '.number_format($hotel->price_per_night_min) : 'Belum diatur' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per Malam (Max)</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->price_per_night_max ? 'Rp '.number_format($hotel->price_per_night_max) : 'Belum diatur' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontak & Website</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->contact_phone ?? 'Belum diatur' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hotel->contact_email ?? 'Belum diatur' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Website</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($hotel->website)
                            <a href="{{ $hotel->website }}" target="_blank" class="text-ocean-600 hover:underline">{{ $hotel->website }}</a>
                        @else
                            Belum diatur
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Kelengkapan</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Persetujuan</label>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($hotel->status === 'approved') bg-green-100 text-green-700
                    @elseif($hotel->status === 'pending') bg-orange-100 text-orange-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst($hotel->status) }}
                </span>
            </div>

            @php
                $completionFields = ['price_per_night_min', 'price_per_night_max', 'contact_phone', 'contact_email', 'website', 'total_rooms', 'thumbnail', 'star_rating'];
                $filledFields = 0;
                foreach($completionFields as $field) {
                    if(!empty($hotel->$field)) $filledFields++;
                }
                $completionPercentage = round(($filledFields / count($completionFields)) * 100);
            @endphp

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelengkapan Data</label>
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                        <div class="bg-ocean-600 h-3 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ $completionPercentage }}%</span>
                </div>
                <p class="text-xs text-gray-600">{{ $filledFields }}/{{ count($completionFields) }} field terisi</p>
            </div>

            <div class="space-y-2">
                <a href="{{ route('mitra.hotels.edit', $hotel) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-ocean-600 text-white rounded-lg hover:bg-ocean-700 transition">
                    <i class="fas fa-edit mr-2"></i> Lengkapi Data
                </a>
                
                @if($hotel->thumbnail)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Thumbnail</label>
                    <img src="{{ $hotel->thumbnail }}" alt="{{ $hotel->name }}" class="w-full h-32 object-cover rounded-lg">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
