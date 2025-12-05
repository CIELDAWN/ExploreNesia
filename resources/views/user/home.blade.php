@extends('layouts.user')

@section('title', 'Beranda')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<!-- Background slideshow full page (di bawah navbar) -->
<div id="hero-slideshow" class="fixed inset-0 top-16 w-full h-full bg-cover bg-center transition-all duration-700 -z-20"></div>
<!-- Overlay tipis agar teks lebih terbaca -->
<div class="fixed inset-0 top-16 w-full h-full bg-black/40 -z-10 pointer-events-none"></div>

<!-- Hero text di atas background -->
<div class="relative flex items-center justify-center mb-8 min-h-[60vh] md:min-h-[70vh] px-6 md:px-10 lg:px-16">
    <div class="text-center text-white space-y-5 max-w-2xl">
        <p class="text-xs md:text-sm uppercase tracking-[0.3em] text-white/80">Selamat datang di ExploreNesia</p>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight drop-shadow-md">
            Halo, {{ auth()->user()->name }}
        </h1>
        <p class="text-sm md:text-base text-white/90 drop-shadow">
            Temukan keindahan destinasi wisata, hotel, dan kuliner terbaik di seluruh Indonesia. Mulai petualanganmu dari sini.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-7 md:px-10 py-3.5 md:py-4 rounded-full bg-ocean-500 hover:bg-ocean-600 text-sm md:text-base font-semibold shadow-xl shadow-black/40 transition-transform transform hover:-translate-y-0.5">
                <i class="fas fa-compass mr-2"></i>
                Jelajah sekarang
            </a>
            <span class="text-xs md:text-sm text-white/90 flex items-center gap-2 drop-shadow">
                <i class="fas fa-map-marked-alt"></i>
                Ratusan destinasi menunggumu.
            </span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-7 mb-6">
    @if (!($hasLocation ?? false))
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-ocean-100 flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-ocean-600"></i>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-semibold text-gray-900 mb-1">Atur lokasi kamu dulu</h2>
                <p class="text-sm text-gray-600 mb-2">Untuk menampilkan rekomendasi destinasi, hotel, dan restoran terdekat, silakan lengkapi provinsi dan kota di profilmu.</p>
                <a href="{{ route('user.profile') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-ocean-500 hover:bg-ocean-600 text-white text-sm font-semibold shadow-sm">
                    <i class="fas fa-user-cog mr-2"></i>
                    Buka Profil
                </a>
            </div>
        </div>
    @else
        <h2 class="text-base md:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-ocean-500"></i>
            Rekomendasi di sekitarmu
        </h2>

        @php
            $hasAnyRecommendations = ($recommendedDestinations->count() + $recommendedHotels->count() + $recommendedRestaurants->count()) > 0;
        @endphp

        @if (! $hasAnyRecommendations)
            <p class="text-sm text-gray-600">Belum ada destinasi, hotel, atau restoran yang terdaftar di sekitar lokasi kamu. Coba jelajahi kategori lain terlebih dahulu.</p>
        @else
            <div class="space-y-4">
                @if ($recommendedDestinations->count())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-map-marked-alt text-ocean-500"></i>
                            Destinasi wisata
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach ($recommendedDestinations as $dest)
                                <a href="{{ route('user.destinations.show', $dest->slug) }}" class="block p-3 rounded-xl border border-gray-100 hover:border-ocean-200 hover:shadow-sm transition">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $dest->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $dest->city->name ?? 'Lokasi tidak diketahui' }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($recommendedHotels->count())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-hotel text-forest-500"></i>
                            Hotel
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach ($recommendedHotels as $hotel)
                                <a href="{{ route('user.hotels.show', $hotel->slug) }}" class="block p-3 rounded-xl border border-gray-100 hover:border-forest-200 hover:shadow-sm transition">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $hotel->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $hotel->city->name ?? 'Lokasi tidak diketahui' }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($recommendedRestaurants->count())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-utensils text-earth-500"></i>
                            Restoran & kuliner
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach ($recommendedRestaurants as $resto)
                                <a href="{{ route('user.restaurants.show', $resto->slug) }}" class="block p-3 rounded-xl border border-gray-100 hover:border-earth-200 hover:shadow-sm transition">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $resto->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $resto->city->name ?? 'Lokasi tidak diketahui' }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-7">
    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fas fa-lightbulb text-yellow-400"></i>
        Tips perjalanan
    </h2>
    <ul class="space-y-3 text-sm md:text-base text-gray-600">
        <li class="flex gap-2">
            <i class="fas fa-check text-ocean-500 mt-1"></i>
            <span>Tambahkan destinasi ke favorit untuk menyimpannya dan melihatnya dengan cepat nanti.</span>
        </li>
        <li class="flex gap-2">
            <i class="fas fa-check text-ocean-500 mt-1"></i>
            <span>Gunakan riwayat perjalanan untuk memberi ulasan setelah trip selesai.</span>
        </li>
        <li class="flex gap-2">
            <i class="fas fa-check text-ocean-500 mt-1"></i>
            <span>Lengkapi profilmu agar mitra lebih mudah menghubungi bila diperlukan.</span>
        </li>
    </ul>
</div>
@endsection

@push('scripts')
<script>
    const heroEl = document.getElementById('hero-slideshow');
    if (heroEl) {
        const images = [
            'https://images.pexels.com/photos/237272/pexels-photo-237272.jpeg?auto=compress&cs=tinysrgb&w=1600',
            'https://images.pexels.com/photos/753626/pexels-photo-753626.jpeg?auto=compress&cs=tinysrgb&w=1600',
            'https://images.pexels.com/photos/208745/pexels-photo-208745.jpeg?auto=compress&cs=tinysrgb&w=1600'
        ];
        let current = 0;

        const applyBackground = () => {
            heroEl.style.backgroundImage = `url('${images[current]}')`;
        };

        applyBackground();

        setInterval(() => {
            current = (current + 1) % images.length;
            applyBackground();
        }, 8000);
    }
</script>
@endpush
