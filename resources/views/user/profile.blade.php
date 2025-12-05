@extends('layouts.user')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun ExploreNesia')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl mx-auto">
    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

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

    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Akun</h2>

    <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent text-sm"
                required
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
                type="email"
                value="{{ $user->email }}"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-sm text-gray-500 cursor-not-allowed"
                disabled
            >
            <p class="mt-1 text-xs text-gray-400">Email tidak dapat diubah.</p>
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone', $user->phone) }}"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent text-sm"
                placeholder="Contoh: 081234567890"
            >
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="province_id" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                <select
                    id="province_id"
                    name="province_id"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent text-sm bg-white"
                >
                    <option value="">Pilih provinsi</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ (int) old('province_id', $user->province_id) === $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">Kota / Kabupaten</label>
                <select
                    id="city_id"
                    name="city_id"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent text-sm bg-white"
                >
                    <option value="">Pilih kota</option>
                    @foreach($cities as $city)
                        <option
                            value="{{ $city->id }}"
                            data-province="{{ $city->province_id }}"
                            {{ (int) old('city_id', $user->city_id) === $city->id ? 'selected' : '' }}
                        >
                            {{ $city->name }} ({{ $city->province->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-between border-t border-gray-100 mt-4">
            <div class="text-xs text-gray-500">
                Status akun:
                <span class="font-semibold {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <button
                type="submit"
                class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-ocean-600 hover:bg-ocean-700 shadow-sm transition"
            >
                <i class="fas fa-save mr-2"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');
        if (!provinceSelect || !citySelect) return;

        const allCityOptions = Array.from(citySelect.options).slice(1); // skip placeholder

        function filterCities() {
            const selectedProvince = provinceSelect.value;

            // Reset to placeholder
            const currentCity = citySelect.value;
            citySelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Pilih kota';
            citySelect.appendChild(placeholder);

            allCityOptions.forEach(option => {
                if (!selectedProvince || option.getAttribute('data-province') === selectedProvince) {
                    citySelect.appendChild(option);
                }
            });

            // Jika city yang tersimpan tidak cocok dengan provinsi baru, kosongkan
            const hasCurrent = Array.from(citySelect.options).some(opt => opt.value === currentCity);
            citySelect.value = hasCurrent ? currentCity : '';
        }

        provinceSelect.addEventListener('change', filterCities);

        // Filter awal sesuai data yang sudah tersimpan
        filterCities();
    });
</script>
@endpush
