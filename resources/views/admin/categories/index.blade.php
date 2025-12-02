@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Kategori Wisata')
@section('page-subtitle', 'Kelola semua kategori destinasi wisata')

@section('content')

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-ocean-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-ocean-100 flex items-center justify-center">
                <i class="fas fa-tags text-ocean-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Kategori</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-forest-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-forest-100 flex items-center justify-center">
                <i class="fas fa-map-marked-alt text-forest-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Destinasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->sum('destinations_count') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-fire text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kategori Populer</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->where('destinations_count', '>', 0)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Avg per Kategori</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->count() > 0 ? round($categories->sum('destinations_count') / $categories->count(), 1) : 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Kategori</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola kategori destinasi wisata</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-6 py-3 bg-ocean-600 text-white rounded-lg font-semibold hover:bg-ocean-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Tambah Kategori
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Destinasi</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="text-3xl">{{ $category->icon }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $category->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <code class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-sm">{{ $category->slug }}</code>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $category->description ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 bg-ocean-100 text-ocean-700 rounded-full text-sm font-semibold">
                            {{ $category->destinations_count }} destinasi
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.categories.show', $category) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-tags text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kategori</h3>
                            <p class="text-gray-600 mb-4">Tambahkan kategori pertama untuk mengklasifikasikan destinasi wisata</p>
                            <a href="{{ route('admin.categories.create') }}" class="px-6 py-3 bg-ocean-600 text-white rounded-lg font-semibold hover:bg-ocean-700 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Kategori
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $categories->links() }}
    </div>
    @endif
</div>

@endsection