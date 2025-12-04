@extends('layouts.user')

@section('title', $hotel->name)
@section('page-title', $hotel->name)
@section('page-subtitle', $hotel->city->name ?? 'Lokasi tidak diketahui')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-2/3">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">Tentang Hotel</h2>
            <p class="text-gray-700 leading-relaxed">{{ $hotel->description }}</p>
        </div>
        <div class="md:w-1/3 bg-gray-50 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Kontak</h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li><i class="fas fa-map-marker-alt text-ocean-600 mr-2"></i>{{ $hotel->address }}</li>
                @if($hotel->contact_phone)
                    <li><i class="fas fa-phone text-ocean-600 mr-2"></i>{{ $hotel->contact_phone }}</li>
                @endif
                @if($hotel->contact_email)
                    <li><i class="fas fa-envelope text-ocean-600 mr-2"></i>{{ $hotel->contact_email }}</li>
                @endif
                @if($hotel->website)
                    <li><i class="fas fa-globe text-ocean-600 mr-2"></i><a href="{{ $hotel->website }}" target="_blank" class="text-ocean-600 hover:underline">{{ $hotel->website }}</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection

