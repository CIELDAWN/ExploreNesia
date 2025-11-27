@extends('layouts.mitra')

@section('title', 'Promo dan Diskon')
@section('page-title', 'Promo Khusus')
@section('page-subtitle', 'Tambahkan promo menarik untuk meningkatkan pemesanan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">Daftar Promo</h3>
        <p class="text-sm text-gray-500">Aktifkan atau hentikan promo yang sedang berjalan.</p>
    </div>
    <a href="{{ route('mitra.promotions.create') }}" class="inline-flex items-center px-4 py-2 bg-ocean-600 text-white rounded-lg shadow hover:bg-ocean-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Promo
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($promotions as $promotion)
            <tr>
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900">{{ $promotion->title }}</p>
                    <p class="text-xs text-gray-500">{{ $promotion->promotionable->name ?? '-' }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                    {{ ucfirst(class_basename($promotion->promotionable_type)) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                    {{ $promotion->start_date->format('d M Y') }} - {{ $promotion->end_date->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $promotion->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $promotion->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm space-x-2">
                    <a href="{{ route('mitra.promotions.edit', $promotion) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-lg text-gray-700 hover:border-ocean-500 hover:text-ocean-600">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('mitra.promotions.destroy', $promotion) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus promo ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center px-3 py-1.5 border border-red-200 rounded-lg text-red-600 hover:bg-red-50">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    Belum ada promo aktif. <a href="{{ route('mitra.promotions.create') }}" class="text-ocean-600 underline">Tambah promo pertama</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $promotions->links() }}
</div>
@endsection






