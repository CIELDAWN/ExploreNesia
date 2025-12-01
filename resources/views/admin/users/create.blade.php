@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Pengguna Baru')
@section('page-subtitle', 'Buat akun pengguna baru')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-ocean-600 hover:text-ocean-700 font-medium">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-200">
            <div class="w-16 h-16 bg-gradient-to-br from-ocean-500 to-ocean-700 rounded-xl flex items-center justify-center text-white">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Pengguna Baru</h2>
                <p class="text-gray-600">Isi form di bawah untuk membuat akun baru</p>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-ocean-600 mr-2"></i>
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border-2border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none transition @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                    @error('name')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-ocean-600 mr-2"></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border-2border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none transition @error('email') border-red-500 @enderror"
                        placeholder="nama@email.com"
                        required
                    >
                    @error('email')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-ocean-600 mr-2"></i>
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 pr-12 border-2border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none transition @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter"
                            required
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        >
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-info-circle"></i>
                        Password minimal 8 karakter
                    </p>
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tag text-ocean-600 mr-2"></i>
                        Role / Peran <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        class="w-full px-4 py-3 border-2border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none transition @error('role') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="mitra" {{ old('role') == 'mitra' ? 'selected' : '' }}>Mitra</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <div class="mt-3 space-y-2 text-sm">
                        <p class="text-gray-600">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded font-medium">Admin</span>
                            - Akses penuh ke seluruh sistem
                        </p>
                        <p class="text-gray-600">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded font-medium">Mitra</span>
                            - Kelola bisnis (hotel, restoran, destinasi)
                        </p>
                        <p class="text-gray-600">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded font-medium">User</span>
                            - Pengguna biasa (wisatawan)
                        </p>
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-phone text-ocean-600 mr-2"></i>
                        No. Telepon
                    </label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border-2border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none transition @error('phone') border-red-500 @enderror"
                        placeholder="08123456789"
                    >
                    @error('phone')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-ocean-600 mr-2"></i>
                        Alamat
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="w-full px-4 py-3 border-2border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none transition @error('address') border-red-500 @enderror"
                        placeholder="Masukkan alamat lengkap"
                    >{{ old('address') }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="flex-1 bg-ocean-500 text-white py-3 px-6 rounded-lg hover:bg-ocean-600 transition font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                >
                    <i class="fas fa-save"></i>
                    Simpan Pengguna
                </button>
                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-300 transition font-semibold text-center"
                >
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
@endsection
