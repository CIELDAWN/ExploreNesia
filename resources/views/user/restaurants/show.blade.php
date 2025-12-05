@extends('layouts.user')

@section('title', $restaurant->name)
@section('page-title', $restaurant->name)
@section('page-subtitle', $restaurant->city->name ?? 'Lokasi tidak diketahui')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Detail Restoran -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($restaurant->thumbnail)
                <img src="{{ asset('storage/' . $restaurant->thumbnail) }}" alt="{{ $restaurant->name }}" class="w-full h-72 object-cover">
            @else
                <div class="w-full h-72 bg-gradient-to-br from-ocean-400 to-ocean-600 flex items-center justify-center text-white text-5xl">
                    <i class="fas fa-utensils"></i>
                </div>
            @endif

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="inline-block px-3 py-1 bg-ocean-100 text-ocean-700 text-xs font-semibold rounded-full">
                        <i class="fas fa-utensils mr-1"></i> Restoran Mitra
                    </span>
                    <div class="text-sm text-gray-500 flex items-center gap-2">
                        <i class="fas fa-eye"></i> {{ number_format($restaurant->view_count ?? 0) }} dilihat
                    </div>
                </div>

                <p class="text-gray-700 leading-relaxed">{{ $restaurant->description }}</p>

                <div class="space-y-2 text-sm text-gray-700">
                    <div><i class="fas fa-map-marker-alt text-ocean-600 mr-2"></i>{{ $restaurant->address }}</div>
                    @if($restaurant->contact_phone)
                        <div><i class="fas fa-phone text-ocean-600 mr-2"></i>{{ $restaurant->contact_phone }}</div>
                    @endif
                    @if($restaurant->contact_email)
                        <div><i class="fas fa-envelope text-ocean-600 mr-2"></i>{{ $restaurant->contact_email }}</div>
                    @endif
                    @if($restaurant->website)
                        <div><i class="fas fa-globe text-ocean-600 mr-2"></i>
                            <a href="{{ $restaurant->website }}" target="_blank" class="text-ocean-600 hover:underline">
                                {{ $restaurant->website }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Pemesanan Restoran -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-check text-ocean-600"></i>
                Reservasi Meja
            </h2>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-3 rounded-r-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('user.restaurants.book', $restaurant->slug) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500"
                           min="{{ now()->toDateString() }}" required>
                    @error('visit_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Orang</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500"
                           required>
                    @error('quantity')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500"
                              placeholder="Contoh: permintaan kursi outdoor, alergi makanan, dll.">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Perkiraan Harga per Orang</p>
                        @if($restaurant->average_price_min > 0)
                            <p class="text-lg font-semibold text-ocean-600">
                                Rp {{ number_format($restaurant->average_price_min, 0, ',', '.') }}
                                @if($restaurant->average_price_max && $restaurant->average_price_max > $restaurant->average_price_min)
                                    <span class="text-sm text-gray-500">- Rp {{ number_format($restaurant->average_price_max, 0, ',', '.') }}</span>
                                @endif
                                <span class="text-sm text-gray-500">/orang</span>
                            </p>
                        @else
                            <p class="text-lg font-semibold text-gray-500">Harga belum tersedia</p>
                        @endif
                    </div>
                    <div class="text-right text-xs text-gray-500">
                        Pembayaran dan detail lebih lanjut akan dikonfirmasi oleh mitra restoran.
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-ocean-600 text-white py-3 rounded-lg font-semibold hover:bg-ocean-700 transition">
                    <i class="fas fa-concierge-bell mr-2"></i>Reservasi Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection



