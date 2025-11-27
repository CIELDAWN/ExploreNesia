@extends('layouts.mitra')

@section('title', 'Kelola Restoran')
@section('page-title', 'Restoran')
@section('page-subtitle', 'Kelola menu, jam operasional, dan promo restoran')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">Daftar Restoran</h3>
        <p class="text-sm text-gray-500">Perbarui informasi restoran Anda secara berkala.</p>
    </div>
    <a href="{{ route('mitra.restaurants.create') }}" class="inline-flex items-center px-4 py-2 bg-ocean-600 text-white rounded-lg shadow hover:bg-ocean-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Restoran
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
            @forelse($restaurants as $restaurant)
            <tr>
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900">{{ $restaurant->name }}</p>
                    <p class="text-xs text-gray-500">{{ $restaurant->opening_time }} - {{ $restaurant->closing_time }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $restaurant->city->name ?? '-' }}</td>
                <td class="px-6 py-4">
                    @php
                        $completionFields = ['average_price_min', 'average_price_max', 'opening_time', 'closing_time', 'contact_phone', 'contact_email', 'website', 'capacity', 'thumbnail', 'cuisine_types'];
                        $filledFields = 0;
                        foreach($completionFields as $field) {
                            if(!empty($restaurant->$field)) $filledFields++;
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
                        @if($restaurant->status === 'approved') bg-green-100 text-green-700
                        @elseif($restaurant->status === 'pending') bg-orange-100 text-orange-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ ucfirst($restaurant->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm space-x-2">
                    <a href="{{ route('mitra.restaurants.show', $restaurant) }}" class="inline-flex items-center px-3 py-1.5 border border-blue-200 rounded-lg text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-eye mr-1"></i> Lihat
                    </a>
                    <a href="{{ route('mitra.restaurants.edit', $restaurant) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-lg text-gray-700 hover:border-ocean-500 hover:text-ocean-600">
                        <i class="fas fa-edit mr-1"></i> Lengkapi
                    </a>
                    <form action="{{ route('mitra.restaurants.destroy', $restaurant) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus restoran ini?');">
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
                    Belum ada restoran. <a href="{{ route('mitra.restaurants.create') }}" class="text-ocean-600 underline">Tambah sekarang</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $restaurants->links() }}
</div>
@endsection






