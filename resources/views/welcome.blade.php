<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExploreNesia - Jelajahi Keindahan Indonesia</title>
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
        
        .gradient-ocean {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
        }
        
        .gradient-forest {
            background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 188, 212, 0.9) 0%, rgba(0, 151, 167, 0.9) 100%);
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-plane-departure text-ocean-600 text-3xl"></i>
                    <h1 class="text-2xl font-bold text-gray-800">ExploreNesia</h1>
                </div>
                
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home" class="text-gray-700 hover:text-ocean-600 font-medium transition">Beranda</a>
                    <a href="#destinations" class="text-gray-700 hover:text-ocean-600 font-medium transition">Destinasi</a>
                    <a href="#about" class="text-gray-700 hover:text-ocean-600 font-medium transition">Tentang</a>
                    <a href="#contact" class="text-gray-700 hover:text-ocean-600 font-medium transition">Kontak</a>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="px-6 py-2 text-ocean-600 font-semibold hover:text-ocean-700 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 gradient-ocean text-white font-semibold rounded-lg hover:shadow-lg transition">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-20">
        <!-- Background Image Overlay -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1920');">
            <div class="absolute inset-0 hero-gradient"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-block mb-4 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                    <span class="text-white font-medium">🇮🇩 Discover Indonesia</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                    Jelajahi Keindahan
                    <span class="block text-yellow-300">Indonesia</span>
                </h1>
                
                <p class="text-xl text-white/90 mb-8 max-w-2xl">
                    Temukan destinasi wisata terbaik, hotel nyaman, dan kuliner lezat di seluruh nusantara. 
                    Mulai petualanganmu sekarang!
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-ocean-700 font-bold rounded-lg hover:bg-gray-100 transition shadow-lg">
                        <i class="fas fa-compass mr-2"></i>
                        Mulai Jelajah
                    </a>
                    <a href="#destinations" class="px-8 py-4 bg-white/20 backdrop-blur-sm text-white font-bold rounded-lg hover:bg-white/30 transition border-2 border-white">
                        <i class="fas fa-play-circle mr-2"></i>
                        Lihat Video
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="mt-16 grid grid-cols-3 gap-8">
                    <div class="text-white">
                        <h3 class="text-4xl font-bold mb-2">1000+</h3>
                        <p class="text-white/80">Destinasi</p>
                    </div>
                    <div class="text-white">
                        <h3 class="text-4xl font-bold mb-2">500+</h3>
                        <p class="text-white/80">Hotel</p>
                    </div>
                    <div class="text-white">
                        <h3 class="text-4xl font-bold mb-2">10K+</h3>
                        <p class="text-white/80">Wisatawan</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-white text-3xl"></i>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Mengapa Pilih ExploreNesia?</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Platform terpercaya untuk merencanakan liburan sempurna di Indonesia
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div class="w-16 h-16 gradient-ocean rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-map-marked-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Destinasi Lengkap</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Temukan ribuan destinasi wisata dari Sabang sampai Merauke dengan informasi lengkap dan terpercaya.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div class="w-16 h-16 gradient-forest rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-ticket-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Pemesanan Mudah</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pesan destinasi, hotel, dan restoran dalam satu platform tanpa ribet. Hemat waktu dan tenaga!
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-earth-500 to-earth-600 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Review Terpercaya</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Baca review dari ribuan traveler lain dan buat keputusan terbaik untuk liburanmu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinations Preview Section -->
    <section id="destinations" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Destinasi Populer</h2>
                <p class="text-gray-600 text-lg">Jelajahi tempat-tempat menakjubkan di Indonesia</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Destination Card 1 -->
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800');"></div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-map-marker-alt text-ocean-600"></i>
                            <span class="text-sm text-gray-600">Bali</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pantai Kuta</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">(120 reviews)</span>
                        </div>
                        <p class="text-sm text-gray-600">Mulai dari <span class="text-ocean-600 font-bold">Gratis</span></p>
                    </div>
                </div>
                
                <!-- Destination Card 2 -->
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1596422846543-75c6fc197f07?w=800');"></div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-map-marker-alt text-ocean-600"></i>
                            <span class="text-sm text-gray-600">Yogyakarta</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Candi Borobudur</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">(250 reviews)</span>
                        </div>
                        <p class="text-sm text-gray-600">Mulai dari <span class="text-ocean-600 font-bold">Rp 50.000</span></p>
                    </div>
                </div>
                
                <!-- Destination Card 3 -->
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1555400082-3b0b2ad63ca5?w=800');"></div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-map-marker-alt text-ocean-600"></i>
                            <span class="text-sm text-gray-600">Jakarta</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Monas</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">(89 reviews)</span>
                        </div>
                        <p class="text-sm text-gray-600">Mulai dari <span class="text-ocean-600 font-bold">Rp 10.000</span></p>
                    </div>
                </div>
                
                <!-- Destination Card 4 -->
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1601272690868-b47a88c7e5ed?w=800');"></div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-map-marker-alt text-ocean-600"></i>
                            <span class="text-sm text-gray-600">Jawa Timur</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Gunung Bromo</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">(340 reviews)</span>
                        </div>
                        <p class="text-sm text-gray-600">Mulai dari <span class="text-ocean-600 font-bold">Rp 34.000</span></p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 gradient-ocean text-white font-semibold rounded-lg hover:shadow-lg transition">
                    Lihat Semua Destinasi
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-ocean">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Siap Memulai Petualangan?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan traveler lain dan temukan pengalaman wisata terbaik di Indonesia
            </p>
            <a href="{{ route('login') }}" class="inline-block px-10 py-4 bg-white text-ocean-700 font-bold rounded-lg hover:bg-gray-100 transition shadow-xl">
                <i class="fas fa-rocket mr-2"></i>
                Mulai Sekarang - Gratis!
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fas fa-plane-departure text-ocean-400 text-2xl"></i>
                        <h3 class="text-xl font-bold">ExploreNesia</h3>
                    </div>
                    <p class="text-gray-400">
                        Platform wisata terpercaya untuk menjelajahi keindahan Indonesia.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">Destinasi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">Kontak</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-ocean-400 transition">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Follow Us</h4>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-ocean-500 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-ocean-500 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-ocean-500 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-ocean-500 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2025 ExploreNesia. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>