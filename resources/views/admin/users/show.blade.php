@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')
@section('page-subtitle', 'Informasi lengkap pengguna')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-ocean-600 hover:text-ocean-700 font-medium">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column - User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">

                <div class="text-center mb-6">
                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-ocean-500 to-ocean-700 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $user->name }}</h2>
                    <p class="text-gray-600 flex items-center justify-center gap-2">
                        <i class="fas fa-envelope text-ocean-600"></i>
                        {{ $user->email }}
                    </p>
                </div>

                <div class="flex justify-center mb-6">
                    @if($user->role == 'admin')
                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-semibold flex items-center gap-2">
                            <i class="fas fa-user-shield"></i>
                            Administrator
                        </span>
                    @elseif($user->role == 'mitra')
                        <span class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full font-semibold flex items-center gap-2">
                            <i class="fas fa-store"></i>
                            Mitra Bisnis
                        </span>
                    @else
                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold flex items-center gap-2">
                            <i class="fas fa-user"></i>
                            Pengguna
                        </span>
                    @endif
                </div>

                <div class="flex justify-center mb-6">
                    @if($user->is_active)
                        <span class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold flex items-center gap-2 shadow-md">
                            <i class="fas fa-check-circle"></i>
                            Akun Aktif
                        </span>
                    @else
                        <span class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold flex items-center gap-2 shadow-md">
                            <i class="fas fa-times-circle"></i>
                            Akun Nonaktif
                        </span>
                    @endif
                </div>

                <div class="space-y-3">
                    <a
                        href="{{ route('admin.users.edit', $user) }}"
                        class="w-full bg-ocean-500 text-white py-3 px-4 rounded-lg hover:bg-ocean-600 transition font-semibold flex items-center justify-center gap-2 shadow-md hover:shadow-lg"
                    >
                        <i class="fas fa-edit"></i>
                        Edit Pengguna
                    </a>

                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" onsubmit="return confirmToggle()">
                        @csrf
                        @method('PATCH')
                        @if($user->is_active)
                            <button
                                type="submit"
                                class="w-full bg-red-500 text-white py-3 px-4 rounded-lg hover:bg-red-600 transition font-semibold flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-user-slash"></i>
                                Nonaktifkan Akun
                            </button>
                        @else
                            <button
                                type="submit"
                                class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition font-semibold flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-user-check"></i>
                                Aktifkan Akun
                            </button>
                        @endif
                    </form>

                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="w-full bg-gray-200 text-red-600 py-3 px-4 rounded-lg hover:bg-red-50 transition font-semibold flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-trash"></i>
                            Hapus Pengguna
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>

        <!-- Right Column - User Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Personal Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-id-card text-ocean-600"></i>
                    Informasi Personal
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="text-gray-800 font-semibold">{{ $user->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="text-gray-800 font-semibold">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                        <p class="text-gray-800 font-semibold">
                            @if($user->phone)
                                <i class="fas fa-phone text-ocean-600 mr-1"></i>
                                {{ $user->phone }}
                            @else
                                <span class="text-gray-400 italic">Belum diisi</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Role</p>
                        <p class="text-gray-800 font-semibold capitalize">{{ $user->role }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 mb-1">Alamat</p>
                        <p class="text-gray-800 font-semibold">
                            @if($user->address)
                                <i class="fas fa-map-marker-alt text-ocean-600 mr-1"></i>
                                {{ $user->address }}
                            @else
                                <span class="text-gray-400 italic">Belum diisi</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Email Verification Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-envelope-circle-check text-ocean-600"></i>
                    Verifikasi Email
                </h3>

                <div class="flex items-center justify-between p-4 {{ $user->email_verified_at ? 'bg-green-50 border-2 border-green-200' : 'bg-yellow-50 border-2 border-yellow-200' }} rounded-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 {{ $user->email_verified_at ? 'bg-green-500' : 'bg-yellow-500' }} rounded-full flex items-center justify-center text-white">
                            @if($user->email_verified_at)
                                <i class="fas fa-check-circle text-3xl"></i>
                            @else
                                <i class="fas fa-exclamation-circle text-3xl"></i>
                            @endif
                        </div>
                        <div>
                            @if($user->email_verified_at)
                                <p class="font-bold text-green-800 text-lg">Email Terverifikasi</p>
                                <p class="text-sm text-green-700">Diverifikasi pada {{ $user->email_verified_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $user->email_verified_at->diffForHumans() }}</p>
                            @else
                                <p class="font-bold text-yellow-800 text-lg">Email Belum Terverifikasi</p>
                                <p class="text-sm text-yellow-700">User belum memverifikasi alamat emailnya</p>
                            @endif
                        </div>
                    </div>

                    <!-- Toggle Verification Button -->
                    <form action="{{ route('admin.users.toggle-verification', $user) }}" method="POST" onsubmit="return confirmVerification()">
                        @csrf
                        @method('PATCH')
                        @if($user->email_verified_at)
                            <button
                                type="submit"
                                class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold flex items-center gap-2 shadow-md"
                                title="Tandai sebagai belum terverifikasi"
                            >
                                <i class="fas fa-times-circle"></i>
                                Unverify
                            </button>
                        @else
                            <button
                                type="submit"
                                class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold flex items-center gap-2 shadow-md"
                                title="Verifikasi email secara manual"
                            >
                                <i class="fas fa-check-circle"></i>
                                Verifikasi
                            </button>
                        @endif
                    </form>
                </div>

                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <strong>Informasi:</strong>
                    </p>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600 ml-6 list-disc">
                        <li>Admin dapat memverifikasi email user secara manual</li>
                        <li>Email terverifikasi diperlukan untuk fitur tertentu</li>
                        <li>User juga bisa verifikasi sendiri melalui link email</li>
                    </ul>
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-ocean-600"></i>
                    Status Akun
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 {{ $user->is_active ? 'bg-green-50' : 'bg-red-50' }} rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Status Login</p>
                        @if($user->is_active)
                            <p class="text-green-700 font-bold flex items-center gap-2">
                                <i class="fas fa-check-circle text-xl"></i>
                                Dapat Login
                            </p>
                        @else
                            <p class="text-red-700 font-bold flex items-center gap-2">
                                <i class="fas fa-ban text-xl"></i>
                                Tidak Dapat Login
                            </p>
                        @endif
                    </div>

                    <div class="p-4 {{ $user->email_verified_at ? 'bg-green-50' : 'bg-yellow-50' }} rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Verifikasi Email</p>
                        @if($user->email_verified_at)
                            <p class="text-green-700 font-bold flex items-center gap-2">
                                <i class="fas fa-check-circle text-xl"></i>
                                Terverifikasi
                            </p>
                        @else
                            <p class="text-yellow-700 font-bold flex items-center gap-2">
                                <i class="fas fa-exclamation-circle text-xl"></i>
                                Belum Terverifikasi
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Timeline -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-clock text-ocean-600"></i>
                    Timeline Akun
                </h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Akun Dibuat</p>
                            <p class="text-sm text-gray-600">{{ $user->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Terakhir Diupdate</p>
                            <p class="text-sm text-gray-600">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($user->email_verified_at)
                    <div class="flex items-start gap-4 p-4 bg-green-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-envelope-circle-check"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-green-800">Email Diverifikasi</p>
                            <p class="text-sm text-green-700">{{ $user->email_verified_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email_verified_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif

                    @if($user->deleted_at)
                    <div class="flex items-start gap-4 p-4 bg-red-50 rounded-lg">
                        <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-red-800">Soft Deleted</p>
                            <p class="text-sm text-red-600">{{ $user->deleted_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-red-500">{{ $user->deleted_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
function confirmToggle() {
    const action = {{ $user->is_active ? 'true' : 'false' }} ? 'menonaktifkan' : 'mengaktifkan';
    const message = {{ $user->is_active ? 'true' : 'false' }}
        ? 'Yakin ingin menonaktifkan akun {{ $user->name }}?\n\nUser tidak akan bisa login dan akan menerima notifikasi.'
        : 'Yakin ingin mengaktifkan kembali akun {{ $user->name }}?\n\nUser akan bisa login kembali ke sistem.';

    return confirm(message);
}

function confirmVerification() {
    const isVerified = {{ $user->email_verified_at ? 'true' : 'false' }};
    const message = isVerified
        ? 'Yakin ingin menandai email {{ $user->email }} sebagai BELUM TERVERIFIKASI?\n\nUser mungkin perlu verifikasi ulang untuk fitur tertentu.'
        : 'Yakin ingin MEMVERIFIKASI email {{ $user->email }} secara manual?\n\nEmail akan ditandai sebagai terverifikasi.';

    return confirm(message);
}
</script>
@endpush
@endsection
