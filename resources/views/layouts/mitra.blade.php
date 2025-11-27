<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mitra Dashboard') - ExploreNesia</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-Vz8bU0Jm6QA0JQ2nXkusN76p8g+Y6R6PCmQwucb5p3s=" crossorigin="anonymous" />

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

        .input-control {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.65rem;
            font-size: 0.95rem;
            color: #1f2937;
            background-color: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-control:focus {
            outline: none;
            border-color: #00ACC1;
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15);
        }

        .map-container {
            height: 320px;
            border-radius: 0.75rem;
            overflow: hidden;
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
                <i class="fas fa-handshake"></i>
                ExploreNesia
            </h1>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 px-4">
            <div class="mb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Main Menu
            </div>

            <a href="{{ route('mitra.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('mitra.dashboard') ? 'active text-white' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <div class="mt-6 mb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Kelola Bisnis
            </div>

            <a href="{{ route('mitra.hotels.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('mitra.hotels.*') ? 'active text-white' : '' }}">
                <i class="fas fa-hotel w-5"></i>
                <span class="font-medium">Hotel</span>
            </a>

            <a href="{{ route('mitra.restaurants.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('mitra.restaurants.*') ? 'active text-white' : '' }}">
                <i class="fas fa-utensils w-5"></i>
                <span class="font-medium">Restoran</span>
            </a>

            <div class="mt-6 mb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Aktivitas
            </div>

            <a href="{{ route('mitra.bookings.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('mitra.bookings.*') ? 'active text-white' : '' }}">
                <i class="fas fa-calendar-check w-5"></i>
                <span class="font-medium">Pemesanan</span>
            </a>

            <a href="{{ route('mitra.reviews.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-ocean-50 {{ request()->routeIs('mitra.reviews.*') ? 'active text-white' : '' }}">
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
                    <p class="text-xs text-gray-500">Mitra</p>
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
                    <p class="text-sm text-gray-500">@yield('page-subtitle', 'Selamat datang di ExploreNesia Mitra Panel')</p>
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

            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
                    <div>
                        <h4 class="text-red-800 font-medium mb-2">Terdapat kesalahan:</h4>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j7kPdCO8jjN6v1sQ6v12Hh0iQp8QUp3xmkG4w/s=" crossorigin="anonymous"></script>
    <script>
        (function () {
            const syncCityUrl = "{{ url('/mitra/geo/sync-city') }}";
            const REGION_DATA = {
                banten: { name: 'Banten', cities: ['Cilegon','Serang','Tangerang','Tangerang Selatan'] },
                dki_jakarta: { name: 'DKI Jakarta', cities: ['Jakarta Barat','Jakarta Pusat','Jakarta Selatan','Jakarta Timur','Jakarta Utara'] },
                jawa_barat: { name: 'Jawa Barat', cities: ['Bandung','Banjar','Bekasi','Bogor','Cimahi','Cirebon','Depok','Sukabumi','Tasikmalaya'] },
                jawa_tengah: { name: 'Jawa Tengah', cities: ['Magelang','Pekalongan','Salatiga','Semarang','Surakarta (Solo)','Tegal'] },
                di_yogyakarta: { name: 'DI Yogyakarta', cities: ['Yogyakarta'] },
                jawa_timur: { name: 'Jawa Timur', cities: ['Batu','Blitar','Kediri','Madiun','Malang','Mojokerto','Pasuruan','Probolinggo','Surabaya'] },
            };

            window.initLocationPicker = function ({
                formId,
                provinceSelectId,
                citySelectId,
                cityHiddenId,
                provinceKeyInputId,
                provinceNameInputId,
                cityNameInputId,
                latInputId,
                lngInputId,
                addressInputId,
                mapId,
            }) {
                const provinceSelect = document.getElementById(provinceSelectId);
                const citySelect = document.getElementById(citySelectId);
                const cityHidden = document.getElementById(cityHiddenId);
                const provinceKeyInput = document.getElementById(provinceKeyInputId);
                const provinceNameInput = document.getElementById(provinceNameInputId);
                const cityNameInput = document.getElementById(cityNameInputId);
                const latInput = document.getElementById(latInputId);
                const lngInput = document.getElementById(lngInputId);
                const addressInput = document.getElementById(addressInputId);
                const form = document.getElementById(formId);
                const mapElement = document.getElementById(mapId);

                if (!provinceSelect || !citySelect || !mapElement || !window.L) {
                    return;
                }

                const map = L.map(mapId).setView([-2.5, 117], 5);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(map);

                let marker = null;
                const setMarker = (lat, lng) => {
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                    latInput.value = lat.toFixed(6);
                    lngInput.value = lng.toFixed(6);
                };

                const reverseGeocode = (lat, lng) => {
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                addressInput.value = data.display_name;
                            }
                        })
                        .catch(() => {});
                };

                map.on('click', (event) => {
                    const { lat, lng } = event.latlng;
                    setMarker(lat, lng);
                    reverseGeocode(lat, lng);
                });

                const loadProvinces = () => {
                    provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    Object.entries(REGION_DATA).forEach(([key, region]) => {
                        const option = document.createElement('option');
                        option.value = key;
                        option.dataset.name = region.name;
                        option.textContent = region.name;
                        provinceSelect.appendChild(option);
                    });

                    if (provinceKeyInput?.value && REGION_DATA[provinceKeyInput.value]) {
                        provinceSelect.value = provinceKeyInput.value;
                        loadCities(provinceKeyInput.value, true);
                    } else {
                        citySelect.disabled = true;
                    }
                };

                const loadCities = (provinceKey, preserveSelection = false) => {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    const region = REGION_DATA[provinceKey];
                    if (!region) {
                        citySelect.disabled = true;
                        return;
                    }
                    region.cities.forEach((cityName) => {
                        const option = document.createElement('option');
                        option.value = cityName;
                        option.dataset.name = cityName;
                        option.textContent = cityName;
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;

                    if (preserveSelection && cityNameInput?.value) {
                        citySelect.value = cityNameInput.value;
                        syncCity();
                    }
                };

                const syncProvinceHidden = () => {
                    const option = provinceSelect.selectedOptions[0];
                    if (provinceKeyInput) {
                        provinceKeyInput.value = option ? option.value : '';
                    }
                    if (provinceNameInput) {
                        provinceNameInput.value = option ? option.dataset.name : '';
                    }
                };

                const syncCityHidden = (cityId, cityName) => {
                    if (cityHidden) cityHidden.value = cityId || '';
                    if (cityNameInput) cityNameInput.value = cityName || '';
                };

                const syncCity = async () => {
                    const provinceOption = provinceSelect.selectedOptions[0];
                    const cityOption = citySelect.selectedOptions[0];
                    if (!provinceOption || !cityOption) {
                        syncCityHidden('', '');
                        return;
                    }

                    syncProvinceHidden();
                    syncCityHidden('', cityOption.dataset.name);

                    try {
                        const response = await fetch(syncCityUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                province_code: provinceOption.value,
                                province_name: provinceOption.dataset.name,
                                city_code: cityOption.value,
                                city_name: cityOption.dataset.name,
                            }),
                        });
                        const data = await response.json();
                        syncCityHidden(data.city_id ?? '', cityOption.dataset.name);
                    } catch (error) {
                        console.error(error);
                        syncCityHidden('', cityOption.dataset.name);
                    }
                };

                provinceSelect.addEventListener('change', (event) => {
                    syncProvinceHidden();
                    cityHidden.value = '';
                    cityNameInput && (cityNameInput.value = '');
                    if (event.target.value) {
                        loadCities(event.target.value);
                    } else {
                        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                        citySelect.disabled = true;
                    }
                });

                citySelect.addEventListener('change', () => {
                    syncCity();
                });

                // Form validation removed - forms will submit normally

                loadProvinces();
            };
        })();
    </script>
    @stack('scripts')
</body>
</html>

