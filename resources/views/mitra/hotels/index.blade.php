@extends('layouts.mitra')

@section('title', 'Kelola Hotel')
@section('page-title', 'Hotel')
@section('page-subtitle', 'Kelola detail hotel, foto, harga, dan ketersediaan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">Daftar Hotel</h3>
        <p class="text-sm text-gray-500">Perbarui informasi hotel untuk menarik lebih banyak pengunjung.</p>
    </div>
    <a href="{{ route('mitra.hotels.create') }}" class="inline-flex items-center px-4 py-2 bg-ocean-600 text-white rounded-lg shadow hover:bg-ocean-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Hotel
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kota</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelengkapan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($hotels as $hotel)
            <tr>
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900">{{ $hotel->name }}</p>
                    <p class="text-xs text-gray-500">Rating: {{ $hotel->star_rating ?? '-' }}★</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $hotel->city->name ?? '-' }}</td>
                <td class="px-6 py-4">
                    @php
                        $completionFields = ['price_per_night_min', 'price_per_night_max', 'contact_phone', 'contact_email', 'website', 'total_rooms', 'thumbnail', 'star_rating'];
                        $filledFields = 0;
                        foreach($completionFields as $field) {
                            if(!empty($hotel->$field)) $filledFields++;
                        }
                        $completionPercentage = round(($filledFields / count($completionFields)) * 100);
                    @endphp
                    <div class="flex items-center gap-2">
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-ocean-600 h-2 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600">{{ $completionPercentage }}%</span>
                    </div>
                    @if($completionPercentage < 100)
                        <p class="text-xs text-orange-600 mt-1">Lengkapi data untuk visibilitas lebih baik</p>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($hotel->status === 'approved') bg-green-100 text-green-700
                        @elseif($hotel->status === 'pending') bg-orange-100 text-orange-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ ucfirst($hotel->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm space-x-2">
                    <a href="{{ route('mitra.hotels.show', $hotel) }}" class="inline-flex items-center px-3 py-1.5 border border-blue-200 rounded-lg text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-eye mr-1"></i> Lihat
                    </a>
                    <a href="{{ route('mitra.hotels.edit', $hotel) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-lg text-gray-700 hover:border-ocean-500 hover:text-ocean-600">
                        <i class="fas fa-edit mr-1"></i> Lengkapi
                    </a>
                    <form action="{{ route('mitra.hotels.destroy', $hotel) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus hotel ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center px-3 py-1.5 border border-red-200 rounded-lg text-red-600 hover:bg-red-50">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    Belum ada hotel. <a href="{{ route('mitra.hotels.create') }}" class="text-ocean-600 underline">Tambah sekarang</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $hotels->links() }}
</div>
@endsection






