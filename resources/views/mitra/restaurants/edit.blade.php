@extends('layouts.mitra')

@section('title', 'Edit Restoran')
@section('page-title', 'Perbarui Restoran')
@section('page-subtitle', 'Lengkapi semua informasi untuk meningkatkan visibilitas')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('mitra.restaurants.update', $restaurant) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Restoran <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" required class="input-control">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <select id="restaurant-province" class="input-control" data-static-options="true">
                <option value="">Pilih Provinsi</option>
                <option value="banten" data-name="Banten">Banten</option>
                <option value="dki-jakarta" data-name="DKI Jakarta">DKI Jakarta</option>
                <option value="jawa-barat" data-name="Jawa Barat">Jawa Barat</option>
                <option value="jawa-tengah" data-name="Jawa Tengah">Jawa Tengah</option>
                <option value="di-yogyakarta" data-name="DI Yogyakarta">DI Yogyakarta</option>
                <option value="jawa-timur" data-name="Jawa Timur">Jawa Timur</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten <span class="text-red-500">*</span></label>
            <select id="restaurant-city" name="city_id" class="input-control" data-static-options="true">
                <option value="">Pilih Kota/Kabupaten</option>
                <!-- Banten -->
                <option value="Cilegon" data-name="Cilegon" data-province="banten">Cilegon</option>
                <option value="Serang" data-name="Serang" data-province="banten">Serang</option>
                <option value="Tangerang" data-name="Tangerang" data-province="banten">Tangerang</option>
                <option value="Tangerang Selatan" data-name="Tangerang Selatan" data-province="banten">Tangerang Selatan</option>
                <!-- DKI Jakarta -->
                <option value="Jakarta Barat" data-name="Jakarta Barat" data-province="dki-jakarta">Jakarta Barat</option>
                <option value="Jakarta Pusat" data-name="Jakarta Pusat" data-province="dki-jakarta">Jakarta Pusat</option>
                <option value="Jakarta Selatan" data-name="Jakarta Selatan" data-province="dki-jakarta">Jakarta Selatan</option>
                <option value="Jakarta Timur" data-name="Jakarta Timur" data-province="dki-jakarta">Jakarta Timur</option>
                <option value="Jakarta Utara" data-name="Jakarta Utara" data-province="dki-jakarta">Jakarta Utara</option>
                <!-- Jawa Barat -->
                <option value="Bandung" data-name="Bandung" data-province="jawa-barat">Bandung</option>
                <option value="Banjar" data-name="Banjar" data-province="jawa-barat">Banjar</option>
                <option value="Bekasi" data-name="Bekasi" data-province="jawa-barat">Bekasi</option>
                <option value="Bogor" data-name="Bogor" data-province="jawa-barat">Bogor</option>
                <option value="Cimahi" data-name="Cimahi" data-province="jawa-barat">Cimahi</option>
                <option value="Cirebon" data-name="Cirebon" data-province="jawa-barat">Cirebon</option>
                <option value="Depok" data-name="Depok" data-province="jawa-barat">Depok</option>
                <option value="Sukabumi" data-name="Sukabumi" data-province="jawa-barat">Sukabumi</option>
                <option value="Tasikmalaya" data-name="Tasikmalaya" data-province="jawa-barat">Tasikmalaya</option>
                <!-- Jawa Tengah -->
                <option value="Magelang" data-name="Magelang" data-province="jawa-tengah">Magelang</option>
                <option value="Pekalongan" data-name="Pekalongan" data-province="jawa-tengah">Pekalongan</option>
                <option value="Salatiga" data-name="Salatiga" data-province="jawa-tengah">Salatiga</option>
                <option value="Semarang" data-name="Semarang" data-province="jawa-tengah">Semarang</option>
                <option value="Surakarta (Solo)" data-name="Surakarta (Solo)" data-province="jawa-tengah">Surakarta (Solo)</option>
                <option value="Tegal" data-name="Tegal" data-province="jawa-tengah">Tegal</option>
                <!-- DI Yogyakarta -->
                <option value="Yogyakarta" data-name="Yogyakarta" data-province="di-yogyakarta">Yogyakarta</option>
                <!-- Jawa Timur -->
                <option value="Batu" data-name="Batu" data-province="jawa-timur">Batu</option>
                <option value="Blitar" data-name="Blitar" data-province="jawa-timur">Blitar</option>
                <option value="Kediri" data-name="Kediri" data-province="jawa-timur">Kediri</option>
                <option value="Madiun" data-name="Madiun" data-province="jawa-timur">Madiun</option>
                <option value="Malang" data-name="Malang" data-province="jawa-timur">Malang</option>
                <option value="Mojokerto" data-name="Mojokerto" data-province="jawa-timur">Mojokerto</option>
                <option value="Pasuruan" data-name="Pasuruan" data-province="jawa-timur">Pasuruan</option>
                <option value="Probolinggo" data-name="Probolinggo" data-province="jawa-timur">Probolinggo</option>
                <option value="Surabaya" data-name="Surabaya" data-province="jawa-timur">Surabaya</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="time" name="opening_time" value="{{ old('opening_time', $restaurant->opening_time) }}" placeholder="08:00" class="input-control">
                    <input type="time" name="closing_time" value="{{ old('closing_time', $restaurant->closing_time) }}" placeholder="22:00" class="input-control">
                </div>
                <p class="text-xs text-gray-500 mt-1">Format: HH:MM (contoh: 08:00 - 22:00)</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required class="input-control">{{ old('description', $restaurant->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
            <input type="text" name="address" value="{{ old('address', $restaurant->address) }}" required class="input-control">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Rata-rata (Min)</label>
                <input type="number" name="average_price_min" value="{{ old('average_price_min', $restaurant->average_price_min) }}" step="1000" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Rata-rata (Max)</label>
                <input type="number" name="average_price_max" value="{{ old('average_price_max', $restaurant->average_price_max) }}" step="1000" class="input-control">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $restaurant->contact_phone) }}" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $restaurant->contact_email) }}" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website', $restaurant->website) }}" class="input-control">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas (orang)</label>
                <input type="number" name="capacity" value="{{ old('capacity', $restaurant->capacity) }}" min="1" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL Thumbnail / Foto</label>
                <input type="url" name="thumbnail" value="{{ old('thumbnail', $restaurant->thumbnail) }}" placeholder="https://example.com/image.jpg" class="input-control">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Masakan</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $availableCuisines = ['Indonesia', 'Chinese', 'Western', 'Japanese', 'Korean', 'Italian', 'Indian', 'Thai', 'Mexican', 'Mediterranean', 'Seafood', 'Vegetarian'];
                    $restaurantCuisines = $restaurant->cuisine_types ?? [];
                @endphp
                @foreach($availableCuisines as $cuisine)
                    <label class="flex items-center gap-2 p-2 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="cuisine_types[]" value="{{ $cuisine }}" 
                               @checked(in_array($cuisine, $restaurantCuisines)) 
                               class="rounded border-gray-300 text-ocean-600 focus:ring-ocean-500">
                        <span class="text-sm text-gray-700">{{ $cuisine }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Restoran</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $availableFacilities = ['WiFi Gratis', 'Parkir', 'AC', 'Smoking Area', 'Non-Smoking', 'Outdoor Seating', 'Private Room', 'Live Music', 'Delivery', 'Takeaway', 'Credit Card', 'Halal'];
                    $restaurantFacilities = $restaurant->facilities ?? [];
                @endphp
                @foreach($availableFacilities as $facility)
                    <label class="flex items-center gap-2 p-2 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="facilities[]" value="{{ $facility }}" 
                               @checked(in_array($facility, $restaurantFacilities)) 
                               class="rounded border-gray-300 text-ocean-600 focus:ring-ocean-500">
                        <span class="text-sm text-gray-700">{{ $facility }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $restaurant->is_active)) class="rounded border-gray-300 text-ocean-600 focus:ring-ocean-500">
            <label class="text-sm text-gray-700">Restoran aktif dan dapat ditampilkan</label>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <div>
                    <h4 class="text-sm font-medium text-blue-900">Tips Meningkatkan Visibilitas</h4>
                    <p class="text-sm text-blue-700 mt-1">Lengkapi semua informasi untuk meningkatkan peluang restoran Anda ditemukan oleh calon pelanggan. Data yang lengkap akan meningkatkan ranking di hasil pencarian.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('mitra.restaurants.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Kembali</a>
            <button class="px-6 py-2 rounded-lg bg-ocean-600 text-white hover:bg-ocean-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const provinceSelect = document.getElementById('restaurant-province');
    const citySelect = document.getElementById('restaurant-city');

    const loadCities = (provinceKey) => {
        const allCityOptions = citySelect.querySelectorAll('option');
        let hasVisibleCities = false;
        
        allCityOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
            } else if (option.dataset.province === provinceKey) {
                option.style.display = 'block';
                hasVisibleCities = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        citySelect.disabled = !hasVisibleCities;
    };

    provinceSelect.addEventListener('change', (event) => {
        if (event.target.value) {
            loadCities(event.target.value);
        } else {
            const allCityOptions = citySelect.querySelectorAll('option');
            allCityOptions.forEach(option => {
                option.style.display = 'block';
            });
            citySelect.disabled = true;
        }
    });

    // Initialize - show current city if exists
    const currentCityName = '{{ $restaurant->city->name ?? "" }}';
    if (currentCityName) {
        // Find and select the current city
        const cityOptions = citySelect.querySelectorAll('option');
        cityOptions.forEach(option => {
            if (option.value === currentCityName) {
                option.selected = true;
                // Also select the corresponding province
                const province = option.dataset.province;
                if (province) {
                    provinceSelect.value = province;
                    loadCities(province);
                }
            }
        });
    }
})();
</script>
@endpush






