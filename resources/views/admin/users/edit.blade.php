@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Perbarui informasi pengguna')

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
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Pengguna</h2>
                <p class="text-gray-600">{{ $user->name }} &bull; {{ $user->email }}</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                        value="{{ old('name', $user->name) }}"
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
                        value="{{ old('email', $user->email) }}"
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
                        Password Baru
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 pr-12 border-2border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none transition @error('password') border-red-500 @enderror"
                            placeholder="Kosongkan jika tidak ingin mengubah password"
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
                        Kosongkan jika tidak ingin mengubah password
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
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="mitra" {{ old('role', $user->role) == 'mitra' ? 'selected' : '' }}>Mitra</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
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
                        value="{{ old('phone', $user->phone) }}"
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
                    >{{ old('address', $user->address) }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-700">
                                <i class="fas fa-toggle-on text-ocean-600 mr-2"></i>
                                Status Akun
                            </p>
                            <p class="text-sm text-gray-600 mt-1">User dapat login jika status aktif</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($user->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-times-circle"></i> Nonaktif
                                </span>
                            @endif
                        </div>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle"></i>
                        Gunakan tombol toggle di halaman daftar user untuk mengubah status
                    </p>
                </div>

                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <strong>Informasi:</strong>
                    </p>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <p>&bull; Akun dibuat: {{ $user->created_at->format('d M Y, H:i') }}</p>
                        <p>&bull; Terakhir diupdate: {{ $user->updated_at->format('d M Y, H:i') }}</p>
                        @if($user->email_verified_at)
                        <p>&bull; Email terverifikasi: {{ $user->email_verified_at->format('d M Y, H:i') }}</p>
                        @else
                        <p class="text-yellow-600">&bull; Email belum terverifikasi</p>
                        @endif
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="flex-1 bg-ocean-500 text-white py-3 px-6 rounded-lg hover:bg-ocean-600 transition font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                >
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
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
