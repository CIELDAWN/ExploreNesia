@extends('layouts.app')

@section('title', 'Favorit Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Favorit Saya</h1>
            <p class="text-gray-600">Destinasi wisata yang Anda simpan sebagai favorit</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- Favorites Grid -->
        @if($favorites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($favorites as $favorite)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <!-- Gambar Destinasi -->
                <div class="relative h-48 bg-gray-200">
                    @if($favorite->destination->image)
                    <img src="{{ Storage::url($favorite->destination->image) }}"
                         alt="{{ $favorite->destination->name }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif

                    <!-- Remove Button -->
                    <form action="{{ route('user.favorites.destroy', $favorite->id) }}"
                          method="POST"
                          class="absolute top-2 right-2"
                          onsubmit="return confirm('Hapus dari favorit?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        {{ $favorite->destination->name }}
                    </h3>

                    <p class="text-sm text-gray-500 mb-2">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $favorite->destination->location }}
                    </p>

                    <!-- Rating -->
                    @if($favorite->destination->reviewsCount() > 0)
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4" fill="{{ $i <= $favorite->destination->averageRating() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-600">
                            {{ $favorite->destination->averageRating() }} ({{ $favorite->destination->reviewsCount() }})
                        </span>
                    </div>
                    @endif

                    <!-- Mitra -->
                    <p class="text-sm text-gray-600 mb-4">
                        Dikelola oleh: <span class="font-medium">{{ $favorite->destination->mitra->name }}</span>
                    </p>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('destinations.show', $favorite->destination->id) }}"
                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{ $favorites->links() }}

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Favorit</h3>
            <p class="text-gray-600 mb-6">Anda belum menyimpan destinasi apapun sebagai favorit</p>
            <a href="{{ route('destinations.index') }}"
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded transition">
                Jelajahi Destinasi
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
