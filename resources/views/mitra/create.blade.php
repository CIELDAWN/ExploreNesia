@extends('layouts.mitra')

@section('title', 'Lengkapi Data Bisnis')
@section('page-title', 'Data Bisnis Baru')
@section('page-subtitle', 'Lengkapi informasi bisnis Anda untuk bergabung dengan ExploreNesia')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('mitra.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Bisnis -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bisnis <span class="text-red-500">*</span></label>
                <input type="text" name="business_name" value="{{ old('business_name') }}" required class="input-control" placeholder="Contoh: Hotel Santika, Restoran Padang Sederhana">
            </div>

            <!-- Jenis Bisnis -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Bisnis <span class="text-red-500">*</span></label>
                <select name="business_type" required class="input-control">
                    <option value="">Pilih Jenis Bisnis</option>
                    <option value="hotel" @selected(old('business_type') == 'hotel')>Hotel</option>
                    <option value="restoran" @selected(old('business_type') == 'restoran')>Restoran</option>
                    <option value="wisata" @selected(old('business_type') == 'wisata')>Wisata</option>
                </select>
            </div>
        </div>

        <!-- Deskripsi Bisnis -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Bisnis <span class="text-red-500">*</span></label>
            <textarea name="business_description" rows="4" required class="input-control" placeholder="Ceritakan tentang bisnis Anda, fasilitas yang tersedia, keunggulan, dll.">{{ old('business_description') }}</textarea>
        </div>

        <!-- Alamat -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
            <textarea name="business_address" rows="2" required class="input-control" placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan">{{ old('business_address') }}</textarea>
        </div>

        <!-- Provinsi dan Kota -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                <select name="province_name" id="province-select" required class="input-control">
                    <option value="">Pilih Provinsi</option>
                    <option value="Banten" @selected(old('province_name') == 'Banten')>Banten</option>
                    <option value="DKI Jakarta" @selected(old('province_name') == 'DKI Jakarta')>DKI Jakarta</option>
                    <option value="Jawa Barat" @selected(old('province_name') == 'Jawa Barat')>Jawa Barat</option>
                    <option value="Jawa Tengah" @selected(old('province_name') == 'Jawa Tengah')>Jawa Tengah</option>
                    <option value="DI Yogyakarta" @selected(old('province_name') == 'DI Yogyakarta')>DI Yogyakarta</option>
                    <option value="Jawa Timur" @selected(old('province_name') == 'Jawa Timur')>Jawa Timur</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten <span class="text-red-500">*</span></label>
                <select name="city_name" id="city-select" required class="input-control" disabled>
                    <option value="">Pilih Kota/Kabupaten</option>
                    <!-- Banten -->
                    <option value="Cilegon" data-province="Banten">Cilegon</option>
                    <option value="Serang" data-province="Banten">Serang</option>
                    <option value="Tangerang" data-province="Banten">Tangerang</option>
                    <option value="Tangerang Selatan" data-province="Banten">Tangerang Selatan</option>
                    <!-- DKI Jakarta -->
                    <option value="Jakarta Barat" data-province="DKI Jakarta">Jakarta Barat</option>
                    <option value="Jakarta Pusat" data-province="DKI Jakarta">Jakarta Pusat</option>
                    <option value="Jakarta Selatan" data-province="DKI Jakarta">Jakarta Selatan</option>
                    <option value="Jakarta Timur" data-province="DKI Jakarta">Jakarta Timur</option>
                    <option value="Jakarta Utara" data-province="DKI Jakarta">Jakarta Utara</option>
                    <!-- Jawa Barat -->
                    <option value="Bandung" data-province="Jawa Barat">Bandung</option>
                    <option value="Banjar" data-province="Jawa Barat">Banjar</option>
                    <option value="Bekasi" data-province="Jawa Barat">Bekasi</option>
                    <option value="Bogor" data-province="Jawa Barat">Bogor</option>
                    <option value="Cimahi" data-province="Jawa Barat">Cimahi</option>
                    <option value="Cirebon" data-province="Jawa Barat">Cirebon</option>
                    <option value="Depok" data-province="Jawa Barat">Depok</option>
                    <option value="Sukabumi" data-province="Jawa Barat">Sukabumi</option>
                    <option value="Tasikmalaya" data-province="Jawa Barat">Tasikmalaya</option>
                    <!-- Jawa Tengah -->
                    <option value="Magelang" data-province="Jawa Tengah">Magelang</option>
                    <option value="Pekalongan" data-province="Jawa Tengah">Pekalongan</option>
                    <option value="Salatiga" data-province="Jawa Tengah">Salatiga</option>
                    <option value="Semarang" data-province="Jawa Tengah">Semarang</option>
                    <option value="Surakarta (Solo)" data-province="Jawa Tengah">Surakarta (Solo)</option>
                    <option value="Tegal" data-province="Jawa Tengah">Tegal</option>
                    <!-- DI Yogyakarta -->
                    <option value="Yogyakarta" data-province="DI Yogyakarta">Yogyakarta</option>
                    <!-- Jawa Timur -->
                    <option value="Batu" data-province="Jawa Timur">Batu</option>
                    <option value="Blitar" data-province="Jawa Timur">Blitar</option>
                    <option value="Kediri" data-province="Jawa Timur">Kediri</option>
                    <option value="Madiun" data-province="Jawa Timur">Madiun</option>
                    <option value="Malang" data-province="Jawa Timur">Malang</option>
                    <option value="Mojokerto" data-province="Jawa Timur">Mojokerto</option>
                    <option value="Pasuruan" data-province="Jawa Timur">Pasuruan</option>
                    <option value="Probolinggo" data-province="Jawa Timur">Probolinggo</option>
                    <option value="Surabaya" data-province="Jawa Timur">Surabaya</option>
                </select>
            </div>
        </div>

        <!-- Informasi Kontak -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="input-control" placeholder="Contoh: 021-1234567 atau 08123456789">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Bisnis</label>
                <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="input-control" placeholder="Contoh: info@bisnis.com">
            </div>
        </div>

        <!-- Website dan Thumbnail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website') }}" class="input-control" placeholder="https://www.bisnis.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Bisnis</label>
                <input type="file" name="thumbnail" accept="image/*" class="input-control">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
            </div>
        </div>

        <!-- Tags Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tags <span class="text-gray-500 text-xs">(Pilih tags yang sesuai dengan bisnis Anda)</span></label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 border border-gray-200 rounded-lg p-4 max-h-64 overflow-y-auto">
                @php
                    $selectedTags = old('tags', []);
                    if (!is_array($selectedTags)) {
                        $selectedTags = [];
                    }
                @endphp
                @foreach($tags ?? [] as $tag)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-ocean-600 focus:ring-ocean-500 cursor-pointer">
                        <span class="ml-2 text-sm text-gray-700 flex items-center cursor-pointer">
                            <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $tag->color ?? '#3B82F6' }}"></span>
                            {{ $tag->name }}
                        </span>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-2">Pilih satu atau lebih tags yang menggambarkan bisnis Anda</p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center pt-6 border-t">
            <a href="{{ route('mitra.dashboard') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">
                Kembali
            </a>
            <button type="submit" class="px-6 py-2 rounded-lg bg-ocean-600 text-white hover:bg-ocean-700">
                <i class="fas fa-save mr-2"></i>
                Simpan Data Bisnis
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province-select');
    const citySelect = document.getElementById('city-select');

    provinceSelect.addEventListener('change', function() {
        const provinceName = this.value;

        // Reset city select
        citySelect.value = '';

        // Show/hide cities based on selected province
        Array.from(citySelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
            } else if (option.dataset.province === provinceName) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });

        // Enable/disable city select
        citySelect.disabled = !provinceName;
    });
});
</script>
@endpush
@endsection
