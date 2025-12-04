@extends('layouts.user')

@section('title', $restaurant->name)
@section('page-title', $restaurant->name)
@section('page-subtitle', $restaurant->city->name ?? 'Lokasi tidak diketahui')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-2/3">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">Tentang Restoran</h2>
            <p class="text-gray-700 leading-relaxed">{{ $restaurant->description }}</p>
        </div>
        <div class="md:w-1/3 bg-gray-50 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Kontak</h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li><i class="fas fa-map-marker-alt text-ocean-600 mr-2"></i>{{ $restaurant->address }}</li>
                @if($restaurant->contact_phone)
                    <li><i class="fas fa-phone text-ocean-600 mr-2"></i>{{ $restaurant->contact_phone }}</li>
                @endif
                @if($restaurant->contact_email)
                    <li><i class="fas fa-envelope text-ocean-600 mr-2"></i>{{ $restaurant->contact_email }}</li>
                @endif
                @if($restaurant->website)
                    <li><i class="fas fa-globe text-ocean-600 mr-2"></i><a href="{{ $restaurant->website }}" target="_blank" class="text-ocean-600 hover:underline">{{ $restaurant->website }}</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection

