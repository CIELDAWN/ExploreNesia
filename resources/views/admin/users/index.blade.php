@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Kelola semua pengguna sistem')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">
                Total: <span class="text-ocean-600">{{ $users->total() }}</span> pengguna
            </h3>
        </div>

        <div class="flex flex-wrap gap-3">
            <!-- Search -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau email..."
                        class="pl-10 pr-4 py-2 border-2 border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none w-64"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Filter Role -->
                <select
                    name="role"
                    onchange="this.form.submit()"
                    class="px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none"
                >
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="mitra" {{ request('role') == 'mitra' ? 'selected' : '' }}>Mitra</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>

                <!-- Filter Status -->
                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-ocean-500 focus:outline-none"
                >
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                @if(request('search') || request('role') || request('status'))
                <a
                    href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center gap-2"
                >
                    <i class="fas fa-times"></i>
                    Reset
                </a>
                @endif
            </form>

            <!-- Add New User Button -->
            <a
                href="{{ route('admin.users.create') }}"
                class="px-4 py-2 bg-ocean-500 text-white rounded-lg hover:bg-ocean-600 transition flex items-center gap-2 shadow-md hover:shadow-lg"
            >
                <i class="fas fa-plus"></i>
                Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Admin</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['admin'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Mitra</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['mitra'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-store text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total User</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['user'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Nonaktif</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['inactive'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-slash text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pengguna
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Terdaftar
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- User Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-ocean-500 to-ocean-700 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Role Badge -->
                        <td class="px-6 py-4">
                            @if($user->role == 'admin')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold flex items-center gap-1 w-fit">
                                    <i class="fas fa-user-shield"></i>
                                    Admin
                                </span>
                            @elseif($user->role == 'mitra')
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold flex items-center gap-1 w-fit">
                                    <i class="fas fa-store"></i>
                                    Mitra
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold flex items-center gap-1 w-fit">
                                    <i class="fas fa-user"></i>
                                    User
                                </span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold flex items-center gap-1 w-fit">
                                    <i class="fas fa-check-circle"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold flex items-center gap-1 w-fit">
                                    <i class="fas fa-times-circle"></i>
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($user->phone)
                                    <p class="text-gray-800 flex items-center gap-1">
                                        <i class="fas fa-phone text-gray-400"></i>
                                        {{ $user->phone }}
                                    </p>
                                @else
                                    <p class="text-gray-400 italic">-</p>
                                @endif
                            </div>
                        </td>

                        <!-- Created At -->
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Toggle Status Button -->
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline" onsubmit="return confirmToggle('{{ $user->name }}', {{ $user->is_active ? 'true' : 'false' }})">
                                    @csrf
                                    @method('PATCH')
                                    @if($user->is_active)
                                        <button
                                            type="submit"
                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                            title="Nonaktifkan"
                                        >
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    @else
                                        <button
                                            type="submit"
                                            class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition"
                                            title="Aktifkan"
                                        >
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    @endif
                                </form>

                                <!-- Edit Button -->
                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- View Button -->
                                <a
                                    href="{{ route('admin.users.show', $user) }}"
                                    class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition"
                                    title="Detail"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Delete Button -->
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-users text-6xl mb-4"></i>
                                <p class="text-lg font-medium">Tidak ada pengguna ditemukan</p>
                                <p class="text-sm">Coba ubah filter atau tambah pengguna baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
function confirmToggle(userName, isActive) {
    const action = isActive ? 'menonaktifkan' : 'mengaktifkan';
    const message = isActive
        ? `Yakin ingin menonaktifkan akun ${userName}?\n\nUser tidak akan bisa login dan akan menerima notifikasi bahwa akunnya dinonaktifkan.`
        : `Yakin ingin mengaktifkan kembali akun ${userName}?\n\nUser akan bisa login kembali ke sistem.`;

    return confirm(message);
}
</script>
@endpush
@endsection
