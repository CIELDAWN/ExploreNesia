@extends('layouts.user')

@section('title', $destination->name)
@section('page-title', $destination->name)
@section('page-subtitle', $destination->city->name ?? 'Lokasi tidak diketahui')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Detail Destinasi -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($destination->thumbnail)
                <img src="{{ asset('storage/' . $destination->thumbnail) }}" alt="{{ $destination->name }}" class="w-full h-72 object-cover">
            @else
                <div class="w-full h-72 bg-gradient-to-br from-ocean-400 to-ocean-600 flex items-center justify-center text-white text-5xl">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            @endif

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="inline-block px-3 py-1 bg-ocean-100 text-ocean-700 text-xs font-semibold rounded-full">
                        {{ $destination->category->icon ?? '📍' }} {{ $destination->category->name ?? 'Wisata' }}
                    </span>
                    <div class="text-sm text-gray-500 flex items-center gap-2">
                        <i class="fas fa-eye"></i> {{ number_format($destination->view_count) }} dilihat
                    </div>
                </div>

                <p class="text-gray-700 leading-relaxed">
                    {!! nl2br(e($destination->description)) !!}
                </p>

                <div class="space-y-2 text-sm text-gray-700">
                    <div><i class="fas fa-map-marker-alt text-ocean-600 mr-2"></i>{{ $destination->address }}</div>
                    @if($destination->contact_phone)
                        <div><i class="fas fa-phone text-ocean-600 mr-2"></i>{{ $destination->contact_phone }}</div>
                    @endif
                    @if($destination->contact_email)
                        <div><i class="fas fa-envelope text-ocean-600 mr-2"></i>{{ $destination->contact_email }}</div>
                    @endif
                    @if($destination->website)
                        <div><i class="fas fa-globe text-ocean-600 mr-2"></i>
                            <a href="{{ $destination->website }}" target="_blank" class="text-ocean-600 hover:underline">
                                {{ $destination->website }}
                            </a>
                        </div>
                    @endif
                </div>

                @if($destination->tags->count() > 0)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($destination->tags as $tag)
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full text-white"
                                  style="background-color: {{ $tag->color ?? '#3B82F6' }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Destinasi terkait -->
        @if($relatedDestinations->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Destinasi Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($relatedDestinations as $rel)
                    <a href="{{ route('user.destinations.show', $rel->slug) }}" class="flex gap-3 p-3 border border-gray-100 rounded-lg hover:border-ocean-200 hover:bg-ocean-50 transition">
                        <div class="w-14 h-14 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                            @if($rel->thumbnail)
                                <img src="{{ asset('storage/' . $rel->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $rel->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-ocean-600">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $rel->name }}</p>
                            <p class="text-xs text-gray-500 truncate">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $rel->city->name ?? 'N/A' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Kartu Pemesanan -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-check text-ocean-600"></i>
                Pesan Kunjungan
            </h2>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-3 rounded-r-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('user.destinations.book', $destination->slug) }}" method="POST" class="space-y-4">
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
                              placeholder="Contoh: butuh tour guide, datang rombongan sekolah, dll.">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Harga Tiket</p>
                        @if($destination->entrance_fee > 0)
                            <p class="text-lg font-semibold text-ocean-600">
                                Rp {{ number_format($destination->entrance_fee, 0, ',', '.') }} <span class="text-sm text-gray-500">/orang</span>
                            </p>
                        @else
                            <p class="text-lg font-semibold text-forest-600">Gratis</p>
                        @endif
                    </div>
                    <div class="text-right text-xs text-gray-500">
                        Pembayaran dan detail lebih lanjut akan dikonfirmasi oleh mitra.
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-ocean-600 text-white py-3 rounded-lg font-semibold hover:bg-ocean-700 transition">
                    <i class="fas fa-shopping-bag mr-2"></i>Pesan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection




