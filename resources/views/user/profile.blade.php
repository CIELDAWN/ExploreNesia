@extends('layouts.user')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun ExploreNesia')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Akun</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-gray-500">Nama Lengkap</dt>
                    <dd class="text-base text-gray-900 font-medium">{{ auth()->user()->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Email</dt>
                    <dd class="text-base text-gray-900 font-medium">{{ auth()->user()->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Nomor Telepon</dt>
                    <dd class="text-base text-gray-900 font-medium">{{ auth()->user()->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Role</dt>
                    <dd class="text-base text-gray-900 font-medium">{{ ucfirst(auth()->user()->role) }}</dd>
                </div>
            </dl>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Status Akun</h2>
            <div class="p-4 bg-ocean-50 rounded-xl border border-ocean-100">
                <p class="text-sm text-gray-600">Keaktifan Akun</p>
                <p class="text-lg font-semibold text-ocean-600">
                    {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                </p>
                <p class="text-sm text-gray-500 mt-3">
                    Hubungi admin jika Anda perlu memperbarui informasi akun secara lebih lanjut.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

