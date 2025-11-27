@extends('layouts.mitra')

@section('title', 'Edit Hotel')
@section('page-title', 'Perbarui Hotel')
@section('page-subtitle', 'Lengkapi semua informasi untuk meningkatkan visibilitas')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('mitra.hotels.update', $hotel) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Hotel <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $hotel->name) }}" required class="input-control">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <select id="hotel-province" class="input-control" data-static-options="true">
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
            <select id="hotel-city" name="city_id" class="input-control" data-static-options="true">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating Bintang</label>
                <select name="star_rating" class="input-control">
                    <option value="">Pilih Rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(old('star_rating', $hotel->star_rating) == $i)>{{ $i }} Bintang</option>
                    @endfor
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required class="input-control">{{ old('description', $hotel->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
            <input type="text" name="address" value="{{ old('address', $hotel->address) }}" required class="input-control">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Minimal per Malam</label>
                <input type="number" name="price_per_night_min" value="{{ old('price_per_night_min', $hotel->price_per_night_min) }}" step="10000" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Maksimal per Malam</label>
                <input type="number" name="price_per_night_max" value="{{ old('price_per_night_max', $hotel->price_per_night_max) }}" step="10000" class="input-control">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $hotel->contact_phone) }}" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $hotel->contact_email) }}" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website', $hotel->website) }}" class="input-control">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kamar</label>
                <input type="number" name="total_rooms" value="{{ old('total_rooms', $hotel->total_rooms) }}" min="1" class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL Thumbnail / Foto</label>
                <input type="url" name="thumbnail" value="{{ old('thumbnail', $hotel->thumbnail) }}" placeholder="https://example.com/image.jpg" class="input-control">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Hotel</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $availableFacilities = ['WiFi Gratis', 'Kolam Renang', 'Gym/Fitness', 'Spa', 'Restoran', 'Bar/Lounge', 'Parkir Gratis', 'AC', 'Room Service', 'Laundry', 'Business Center', 'Meeting Room'];
                    $hotelFacilities = $hotel->facilities ?? [];
                @endphp
                @foreach($availableFacilities as $facility)
                    <label class="flex items-center gap-2 p-2 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="facilities[]" value="{{ $facility }}" 
                               @checked(in_array($facility, $hotelFacilities)) 
                               class="rounded border-gray-300 text-ocean-600 focus:ring-ocean-500">
                        <span class="text-sm text-gray-700">{{ $facility }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $hotel->is_active)) class="rounded border-gray-300 text-ocean-600 focus:ring-ocean-500">
            <label class="text-sm text-gray-700">Hotel aktif dan dapat ditampilkan</label>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <div>
                    <h4 class="text-sm font-medium text-blue-900">Tips Meningkatkan Visibilitas</h4>
                    <p class="text-sm text-blue-700 mt-1">Lengkapi semua informasi untuk meningkatkan peluang hotel Anda ditemukan oleh calon tamu. Data yang lengkap akan meningkatkan ranking di hasil pencarian.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('mitra.hotels.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Kembali</a>
            <button class="px-6 py-2 rounded-lg bg-ocean-600 text-white hover:bg-ocean-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const provinceSelect = document.getElementById('hotel-province');
    const citySelect = document.getElementById('hotel-city');

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
    const currentCityName = '{{ $hotel->city->name ?? "" }}';
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






