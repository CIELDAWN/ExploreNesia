{{-- resources/views/layouts/user.blade.php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExploreNesia - Dashboard User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-white">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-green-600">ExploreNesia</h1>
                </div>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('user.dashboard') }}" class="text-green-600 font-semibold border-b-2 border-green-600">Beranda</a>
                    <a href="{{ route('user.destinations') }}" class="text-gray-700 hover:text-green-600 font-medium">Destinasi</a>
                    <a href="{{ route('user.favorites.index') }}" class="text-gray-700 hover:text-green-600 font-medium">Favorit</a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Tentang</a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Kontak</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 hidden sm:block">Hai, {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-green-600 font-medium">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-green-400 mb-4">ExploreNesia</h3>
                    <p class="text-gray-300">Jelajahi Keindahan Indonesia Bersama Kami</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="{{ route('user.dashboard') }}" class="hover:text-green-400">Beranda</a></li>
                        <li><a href="{{ route('user.destinations') }}" class="hover:text-green-400">Destinasi</a></li>
                        <li><a href="{{ route('user.favorites.index') }}" class="hover:text-green-400">Favorit</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-green-400">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-green-400">Kontak</a></li>
                        <li><a href="#" class="hover:text-green-400">Bantuan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Hubungi Kami</h4>
                    <p class="text-gray-300">info@explorenesia.com</p>
                    <p class="text-gray-300">+62 812 3456 7890</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 ExploreNesia. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> --}}

{{-- resources/views/layouts/user.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExploreNesia - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ocean': {
                            400: '#26C6DA',
                            500: '#00BCD4',
                            600: '#00ACC1',
                            700: '#0097A7',
                        },
                        'forest': {
                            400: '#66BB6A',
                            500: '#4CAF50',
                            600: '#43A047',
                        },
                        'earth': {
                            500: '#795548',
                            600: '#6D4C41',
                        },
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, rgba(0, 188, 212, 0.95) 0%, rgba(0, 151, 167, 0.95) 100%);
        }

        .sidebar-with-bg {
            background:
                linear-gradient(180deg, rgba(0, 188, 212, 0.92) 0%, rgba(0, 151, 167, 0.92) 100%),
                url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800') center/cover;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .nav-item-active {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Main Layout -->
    <div class="flex min-h-screen">
        <!-- Sidebar dengan Background Foto -->
        <div class="sidebar-with-bg w-64 text-white flex flex-col relative">
            <!-- Overlay untuk readability -->
            <div class="absolute inset-0 bg-ocean-700/30 z-0"></div>

            <!-- Content Sidebar -->
            <div class="relative z-10 flex flex-col h-full">
                <!-- Logo -->
                <div class="p-6 border-b border-white/20">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-plane-departure text-2xl"></i>
                        <h1 class="text-xl font-bold">ExploreNesia</h1>
                    </div>
                </div>

                <!-- User Info -->
                <div class="p-6 border-b border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-user text-lg"></i>
                        </div>
                        <div>
                            <p class="font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-white/80 text-sm">Wisatawan</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('user.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold backdrop-blur-sm transition duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-white/20 nav-item-active' : 'text-white/90 hover:bg-white/20' }}">
                                <i class="fas fa-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.destinations') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold backdrop-blur-sm transition duration-200 {{ request()->routeIs('user.destinations*') ? 'bg-white/20 nav-item-active' : 'text-white/90 hover:bg-white/20' }}">
                                <i class="fas fa-map-marked-alt"></i>
                                Jelajahi Destinasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.favorites.index') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold backdrop-blur-sm transition duration-200 {{ request()->routeIs('user.favorites*') ? 'bg-white/20 nav-item-active' : 'text-white/90 hover:bg-white/20' }}">
                                <i class="fas fa-heart"></i>
                                Favorit Saya
                            </a>
                        </li>
                        <li>
                            <a href="#"
                               class="flex items-center gap-3 px-4 py-3 text-white/90 hover:bg-white/20 rounded-lg transition backdrop-blur-sm">
                                <i class="fas fa-history"></i>
                                Riwayat Kunjungan
                            </a>
                        </li>
                        <li>
                            <a href="#"
                               class="flex items-center gap-3 px-4 py-3 text-white/90 hover:bg-white/20 rounded-lg transition backdrop-blur-sm">
                                <i class="fas fa-star"></i>
                                Ulasan Saya
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Footer Sidebar dengan Background Full -->
                <div class="mt-auto">
                    <!-- Background Full untuk Footer -->
                    <div class="relative">
                        <!-- Background Image untuk bagian bawah -->
                        <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1555400082-3b0b2ad63ca5?w=800');">
                            <div class="absolute inset-0 bg-ocean-700/60 backdrop-blur-sm"></div>
                        </div>

                        <!-- Content Footer -->
                        <div class="absolute inset-0 flex flex-col justify-end p-6">
                            <div class="text-center">
                                <p class="text-white/90 text-sm mb-2">Jelajahi Keindahan</p>
                                <h3 class="text-white font-bold text-lg mb-3">Indonesia</h3>
                                <div class="flex justify-center space-x-2 text-white/80">
                                    <i class="fas fa-mountain"></i>
                                    <i class="fas fa-umbrella-beach"></i>
                                    <i class="fas fa-utensils"></i>
                                    <i class="fas fa-monument"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <div class="p-4 border-t border-white/20 bg-white/10 backdrop-blur-sm">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-white/90 hover:bg-white/20 rounded-lg transition w-full backdrop-blur-sm">
                                <i class="fas fa-sign-out-alt"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600">@yield('page-subtitle', 'Selamat datang di ExploreNesia')</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="p-2 text-gray-600 hover:text-ocean-600 transition">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="w-8 h-8 bg-ocean-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header> --}}

            <!-- Content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
