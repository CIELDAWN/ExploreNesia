<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - ExploreNesia</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ocean': {
                            50: '#E0F7FA',
                            100: '#B2EBF2',
                            500: '#00BCD4',
                            600: '#00ACC1',
                            700: '#0097A7',
                        },
                        'forest': {
                            100: '#C8E6C9',
                            500: '#4CAF50',
                            600: '#43A047',
                        },
                        'earth': {
                            100: '#D7CCC8',
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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .gradient-ocean { background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%); }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <i class="fas fa-plane-departure text-ocean-600 text-2xl"></i>
                        <span class="text-xl font-bold text-gray-800">ExploreNesia</span>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-ocean-600 transition">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-ocean-600 transition">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('user.favorites.index') }}" class="text-gray-700 hover:text-ocean-600 transition">
                        <i class="fas fa-heart mr-2"></i>Favorites
                    </a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 hover:bg-gray-50 px-3 py-2 rounded-lg transition">
                        <div class="w-10 h-10 rounded-full gradient-ocean flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Page Content -->
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-600">@yield('page-subtitle', 'Selamat datang di ExploreNesia')</p>
        </div>
        
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>