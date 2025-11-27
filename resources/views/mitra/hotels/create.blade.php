@extends('layouts.mitra')

@section('title', 'Tambah Hotel')
@section('page-title', 'Hotel Baru')
@section('page-subtitle', 'Masukkan detail hotel Anda')

@section('content')

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('mitra.hotels.store') }}" method="POST" class="space-y-6" id="hotel-form">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Hotel <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required class="input-control">
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
            <select id="hotel-city" class="input-control" data-static-options="true">
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
        <input type="hidden" name="city_id" id="hotel-city-id" value="{{ old('city_id') }}">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required class="input-control">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
            <input type="text" name="address" id="hotel-address" value="{{ old('address') }}" required class="input-control">
        </div>



        <div class="flex justify-between items-center">
            <a href="{{ route('mitra.hotels.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Batal</a>
            <button class="px-6 py-2 rounded-lg bg-ocean-600 text-white hover:bg-ocean-700">Simpan Hotel</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const syncCityUrl = "{{ url('/mitra/geo/sync-city') }}";
    const REGION_DATA = {
        banten: { name: 'Banten', cities: ['Cilegon','Serang','Tangerang','Tangerang Selatan'] },
        'dki-jakarta': { name: 'DKI Jakarta', cities: ['Jakarta Barat','Jakarta Pusat','Jakarta Selatan','Jakarta Timur','Jakarta Utara'] },
        'jawa-barat': { name: 'Jawa Barat', cities: ['Bandung','Banjar','Bekasi','Bogor','Cimahi','Cirebon','Depok','Sukabumi','Tasikmalaya'] },
        'jawa-tengah': { name: 'Jawa Tengah', cities: ['Magelang','Pekalongan','Salatiga','Semarang','Surakarta (Solo)','Tegal'] },
        'di-yogyakarta': { name: 'DI Yogyakarta', cities: ['Yogyakarta'] },
        'jawa-timur': { name: 'Jawa Timur', cities: ['Batu','Blitar','Kediri','Madiun','Malang','Mojokerto','Pasuruan','Probolinggo','Surabaya'] },
    };

    const provinceSelect = document.getElementById('hotel-province');
    const citySelect = document.getElementById('hotel-city');
    const cityHidden = document.getElementById('hotel-city-id');
    const form = document.getElementById('hotel-form');

    const loadCities = (provinceKey) => {
        // Show/hide cities based on selected province
        const allCityOptions = citySelect.querySelectorAll('option');
        let hasVisibleCities = false;
        
        allCityOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block'; // Always show "Pilih Kota" option
            } else if (option.dataset.province === provinceKey) {
                option.style.display = 'block';
                hasVisibleCities = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        citySelect.disabled = !hasVisibleCities;
        if (!hasVisibleCities) {
            citySelect.value = '';
            cityHidden.value = '';
        }
    };

    const syncCity = async () => {
        const provinceOption = provinceSelect.selectedOptions[0];
        const cityOption = citySelect.selectedOptions[0];
        
        console.log('syncCity called:', {
            province: provinceOption?.value,
            city: cityOption?.value,
            cityDataName: cityOption?.dataset?.name
        });
        
        if (!provinceOption || !cityOption || !cityOption.value) {
            cityHidden.value = '';
            console.log('No province or city selected, clearing cityHidden');
            return;
        }

        try {
            const requestData = {
                province_code: provinceOption.value,
                province_name: provinceOption.dataset.name,
                city_code: cityOption.value,
                city_name: cityOption.dataset.name || cityOption.value,
            };
            
            console.log('Sending request:', requestData);
            
            const response = await fetch(syncCityUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(requestData),
            });
            
            const data = await response.json();
            console.log('Response received:', data);
            
            cityHidden.value = data.city_id ?? '';
            console.log('cityHidden.value set to:', cityHidden.value);
        } catch (error) {
            console.error('Error in syncCity:', error);
            cityHidden.value = '';
        }
    };

    provinceSelect.addEventListener('change', (event) => {
        citySelect.value = ''; // Reset city selection
        cityHidden.value = ''; // Reset hidden field
        if (event.target.value) {
            loadCities(event.target.value);
        } else {
            // Show all cities when no province selected
            const allCityOptions = citySelect.querySelectorAll('option');
            allCityOptions.forEach(option => {
                option.style.display = 'block';
            });
            citySelect.disabled = true;
        }
    });

    citySelect.addEventListener('change', () => {
        // Immediately set the hidden field when city is selected
        if (citySelect.value && citySelect.value !== '') {
            cityHidden.value = citySelect.value; // Use city name directly
            console.log('City selected:', citySelect.value, 'Hidden field set to:', cityHidden.value);
        } else {
            cityHidden.value = '';
            console.log('City cleared, hidden field cleared');
        }
    });

    // Form will submit normally without popup validation

    // Add form submission logging
    form.addEventListener('submit', (e) => {
        console.log('Form submitting with data:');
        console.log('Name:', form.querySelector('[name="name"]').value);
        console.log('Description:', form.querySelector('[name="description"]').value);
        console.log('Address:', form.querySelector('[name="address"]').value);
        console.log('Province:', provinceSelect.value);
        console.log('City:', citySelect.value);
        console.log('City ID (hidden):', cityHidden.value);
        
        if (!cityHidden.value) {
            console.error('WARNING: city_id is empty!');
        }
    });
    
    // Initialize
    citySelect.disabled = true;
})();
</script>
@endpush




