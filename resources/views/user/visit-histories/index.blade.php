@extends('layouts.app')

@section('title', 'Riwayat Kunjungan Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Kunjungan</h1>
                <p class="text-gray-600">Destinasi wisata yang sudah Anda kunjungi</p>
            </div>
            <a href="{{ route('user.visit-histories.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kunjungan
            </a>
        </div>

        <!-- Filter Tahun -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" class="flex gap-4 items-center">
                <label class="text-gray-700 font-medium">Filter Tahun:</label>
                <select name="year"
                        class="border border-gray-300 rounded-lg px-4 py-2"
                        onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- Visit Histories List -->
        @if($visitHistories->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($visitHistories as $visit)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex gap-6">
                    <!-- Gambar Destinasi -->
                    <div class="flex-shrink-0">
                        <div class="w-32 h-32 bg-gray-200 rounded-lg overflow-hidden">
                            @if($visit->destination->image)
                            <img src="{{ Storage::url($visit->destination->image) }}"
                                 alt="{{ $visit->destination->name }}"
                                 class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-1">
                                    {{ $visit->destination->name }}
                                </h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $visit->destination->location }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('user.visit-histories.edit', $visit->id) }}"
                                   class="text-blue-500 hover:text-blue-600 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('user.visit-histories.destroy', $visit->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus riwayat kunjungan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Tanggal Kunjungan -->
                        <div class="flex items-center gap-2 text-gray-700 mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">
                                {{ $visit->visit_date->format('d F Y') }}
                            </span>
                            <span class="text-sm text-gray-500">
                                ({{ $visit->visit_date->diffForHumans() }})
                            </span>
                        </div>

                        <!-- Catatan -->
                        @if($visit->notes)
                        <div class="bg-gray-50 rounded-lg p-3 text-gray-700">
                            <p class="text-sm"><strong>Catatan:</strong> {{ $visit->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{ $visitHistories->links() }}

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Riwayat Kunjungan</h3>
            <p class="text-gray-600 mb-6">Anda belum mencatat kunjungan ke destinasi wisata apapun</p>
            <a href="{{ route('user.visit-histories.create') }}"
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded transition">
                Tambah Kunjungan Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
