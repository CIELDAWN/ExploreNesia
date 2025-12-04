<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - ExploreNesia</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Color Palette Indonesia -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ocean': {
                            50: '#E0F7FA',
                            100: '#B2EBF2',
                            200: '#80DEEA',
                            300: '#4DD0E1',
                            400: '#26C6DA',
                            500: '#00BCD4',
                            600: '#00ACC1',
                            700: '#0097A7',
                            800: '#00838F',
                            900: '#006064',
                        },
                        'forest': {
                            50: '#E8F5E9',
                            100: '#C8E6C9',
                            200: '#A5D6A7',
                            300: '#81C784',
                            400: '#66BB6A',
                            500: '#4CAF50',
                            600: '#43A047',
                            700: '#388E3C',
                            800: '#2E7D32',
                            900: '#1B5E20',
                        },
                        'earth': {
                            50: '#EFEBE9',
                            100: '#D7CCC8',
                            200: '#BCAAA4',
                            300: '#A1887F',
                            400: '#8D6E63',
                            500: '#795548',
                            600: '#6D4C41',
                            700: '#5D4037',
                            800: '#4E342E',
                            900: '#3E2723',
                        },
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .sidebar-link {
            transition: all 0.3s ease;
        }
        
        .sidebar-link:hover {
            transform: translateX(8px);
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
            box-shadow: 0 4px 15px rgba(0, 188, 212, 0.3);
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .gradient-ocean {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
        }
        
        .gradient-forest {
            background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
        }
        
        .gradient-earth {
            background: linear-gradient(135deg, #795548 0%, #5D4037 100%);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-50">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 gradient-ocean">
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-plane-departure"></i>
                ExploreNesia
            </h1>
        </div>
        
        <!-- Navigation -->
        <nav class="mt-6 px-4">
            <div class="mb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Main Menu
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('admin.dashboard') ? 'active text-white' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('admin.analytics') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('admin.analytics') ? 'active text-white' : '' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span class="font-medium">Analitik</span>
            </a>
            
            <div class="mt-6 mb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Management
            </div>
            
            <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('admin.users.*') ? 'active text-white' : '' }}">
                <i class="fas fa-users w-5"></i>
                <span class="font-medium">Pengguna</span>
            </a>
            
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('admin.categories.*') ? 'active text-white' : '' }}">
                <i class="fas fa-tags w-5"></i>
                <span class="font-medium">Kategori</span>
            </a>
            
            <a href="{{ route('admin.mitra-submissions.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('admin.mitra-submissions.*') ? 'active text-white' : '' }}">
                <i class="fas fa-building w-5"></i>
                <span class="font-medium">Manajemen Mitra</span>
                @if(\App\Models\Destination::where('status', 'pending')->count() + \App\Models\Hotel::where('status', 'pending')->count() + \App\Models\Restaurant::where('status', 'pending')->count() > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                    {{ \App\Models\Destination::where('status', 'pending')->count() + \App\Models\Hotel::where('status', 'pending')->count() + \App\Models\Restaurant::where('status', 'pending')->count() }}
                </span>
                @endif
            </a>
            
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('admin.reviews.*') ? 'active text-white' : '' }}">
                <i class="fas fa-star w-5"></i>
                <span class="font-medium">Ulasan</span>
            </a>
        </nav>
        
        <!-- User Profile -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-ocean flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="ml-64 min-h-screen">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm sticky top-0 z-40">
            <div class="flex items-center justify-between px-8 py-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-sm text-gray-500">@yield('page-subtitle', 'Selamat datang di ExploreNesia Admin Panel')</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-400 hover:text-ocean-600 transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    
                    <!-- Date -->
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ now()->isoFormat('dddd, D MMMM Y') }}
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-8">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>