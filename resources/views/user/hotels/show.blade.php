@extends('layouts.user')

@section('title', $hotel->name)
@section('page-title', $hotel->name)
@section('page-subtitle', $hotel->city->name ?? 'Lokasi tidak diketahui')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Detail Hotel -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($hotel->thumbnail)
                <img src="{{ asset('storage/' . $hotel->thumbnail) }}" alt="{{ $hotel->name }}" class="w-full h-72 object-cover">
            @else
                <div class="w-full h-72 bg-gradient-to-br from-ocean-400 to-ocean-600 flex items-center justify-center text-white text-5xl">
                    <i class="fas fa-hotel"></i>
                </div>
            @endif

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="inline-block px-3 py-1 bg-ocean-100 text-ocean-700 text-xs font-semibold rounded-full">
                        <i class="fas fa-hotel mr-1"></i> Hotel Mitra
                    </span>
                    <div class="text-sm text-gray-500 flex items-center gap-2">
                        <i class="fas fa-eye"></i> {{ number_format($hotel->view_count ?? 0) }} dilihat
                    </div>
                </div>

                <p class="text-gray-700 leading-relaxed">{{ $hotel->description }}</p>

                <div class="space-y-2 text-sm text-gray-700">
                    <div><i class="fas fa-map-marker-alt text-ocean-600 mr-2"></i>{{ $hotel->address }}</div>
                    @if($hotel->contact_phone)
                        <div><i class="fas fa-phone text-ocean-600 mr-2"></i>{{ $hotel->contact_phone }}</div>
                    @endif
                    @if($hotel->contact_email)
                        <div><i class="fas fa-envelope text-ocean-600 mr-2"></i>{{ $hotel->contact_email }}</div>
                    @endif
                    @if($hotel->website)
                        <div><i class="fas fa-globe text-ocean-600 mr-2"></i>
                            <a href="{{ $hotel->website }}" target="_blank" class="text-ocean-600 hover:underline">
                                {{ $hotel->website }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Pemesanan Hotel -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-check text-ocean-600"></i>
                Pesan Kamar
            </h2>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-3 rounded-r-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('user.hotels.book', $hotel->slug) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Check-in</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500"
                           min="{{ now()->toDateString() }}" required>
                    @error('visit_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kamar</label>
                    <select name="room_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-ocean-500 focus:border-ocean-500" required>
                        <option value="single" {{ old('room_type') === 'single' ? 'selected' : '' }}>Kamar Single (Kasur 1)</option>
                        @if($hotel->price_per_night_max && $hotel->price_per_night_max > 0)
                            <option value="double" {{ old('room_type') === 'double' ? 'selected' : '' }}>Kamar Double (Kasur 2)</option>
                        @endif
                    </select>
                    @error('room_type')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Malam</label>
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
                              placeholder="Contoh: tipe kamar, jumlah tamu, permintaan khusus, dll.">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Perkiraan Harga per Malam</p>
                        @if($hotel->price_per_night_min > 0)
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">Single (Kasur 1):</span>
                                <span class="text-ocean-600 font-bold">
                                    Rp {{ number_format($hotel->price_per_night_min, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-500">/malam</span>
                            </p>
                            @if($hotel->price_per_night_max && $hotel->price_per_night_max > 0)
                                <p class="text-sm text-gray-700 mt-1">
                                    <span class="font-semibold">Double (Kasur 2):</span>
                                    <span class="text-ocean-600 font-bold">
                                        Rp {{ number_format($hotel->price_per_night_max, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-500">/malam</span>
                                </p>
                            @endif
                        @else
                            <p class="text-lg font-semibold text-gray-500">Harga belum tersedia</p>
                        @endif
                    </div>
                    <div class="text-right text-xs text-gray-500">
                        Pembayaran dan detail lebih lanjut akan dikonfirmasi oleh mitra hotel.
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-ocean-600 text-white py-3 rounded-lg font-semibold hover:bg-ocean-700 transition">
                    <i class="fas fa-bed mr-2"></i>Pesan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection



