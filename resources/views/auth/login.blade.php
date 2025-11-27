<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ExploreNesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ocean': {
                            500: '#00BCD4',
                            600: '#00ACC1',
                            700: '#0097A7',
                        },
                        'forest': {
                            500: '#4CAF50',
                        },
                        'earth': {
                            500: '#795548',
                        },
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .gradient-ocean {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Image/Branding -->
        <div class="hidden lg:flex lg:w-1/2 gradient-ocean relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
                <div class="animate-float mb-8">
                    <i class="fas fa-plane-departure text-8xl opacity-90"></i>
                </div>
                <h1 class="text-5xl font-bold mb-4">ExploreNesia</h1>
                <p class="text-xl text-center max-w-md opacity-90">
                    Jelajahi Keindahan Indonesia Bersama Kami
                </p>
                <div class="mt-12 flex gap-8">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-4xl mb-2"></i>
                        <p class="text-sm">1000+ Destinasi</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-hotel text-4xl mb-2"></i>
                        <p class="text-sm">500+ Hotel</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-utensils text-4xl mb-2"></i>
                        <p class="text-sm">300+ Kuliner</p>
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -mb-32 -ml-32"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mt-48 -mr-48"></div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo Mobile -->
                <div class="lg:hidden text-center mb-8">
                    <h1 class="text-3xl font-bold gradient-ocean bg-clip-text text-transparent">
                        <i class="fas fa-plane-departure mr-2"></i>
                        ExploreNesia
                    </h1>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang!</h2>
                        <p class="text-gray-600">Silakan login untuk melanjutkan</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            <div class="flex-1">
                                @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-800">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-ocean-600"></i>
                                Email
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent transition"
                                placeholder="nama@email.com"
                                required
                                autofocus
                            >
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-ocean-600"></i>
                                Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent transition"
                                    placeholder="••••••••"
                                    required
                                >
                                <button 
                                    type="button" 
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-ocean-600 border-gray-300 rounded focus:ring-ocean-500">
                                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                            <a href="#" class="text-sm text-ocean-600 hover:text-ocean-700 font-medium">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full gradient-ocean text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk
                        </button>
                    </form>

                    <!-- Demo Accounts Info -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Demo Accounts:</p>
                        <div class="space-y-1 text-xs text-gray-600">
                            <p><strong>Admin:</strong> admin@explorenesia.com / password123</p>
                            <p><strong>Mitra:</strong> mitra.bali@explorenesia.com / password123</p>
                            <p><strong>User:</strong> budi@example.com / password123</p>
                        </div>
                    </div>
                </div>

                <p class="text-center text-sm text-gray-600 mt-6">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-ocean-600 hover:text-ocean-700 font-medium">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>