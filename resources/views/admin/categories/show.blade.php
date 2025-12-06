@extends('layouts.admin')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori')
@section('page-subtitle', $category->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $category->name }}</h2>
                @if($category->description)
                    <p class="text-gray-600 mt-1">{{ $category->description }}</p>
                @endif
            </div>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-ocean-50 text-ocean-600">
                <i class="{{ $category->icon }}"></i>
            </span>
        </div>

        <div class="text-sm text-gray-500 space-y-1">
            <p><span class="font-semibold">Slug:</span> {{ $category->slug }}</p>
            <p><span class="font-semibold">Dibuat:</span> {{ $category->created_at?->format('d M Y H:i') }}</p>
            <p><span class="font-semibold">Diupdate:</span> {{ $category->updated_at?->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-ocean-600"></i>
                Destinasi dalam Kategori ini
            </h3>
        </div>

        @if($category->destinations->isEmpty())
            <p class="text-gray-500 text-sm">Belum ada destinasi dalam kategori ini.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-gray-600">
                            <th class="px-4 py-2">Nama Destinasi</th>
                            <th class="px-4 py-2">Kota</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->destinations as $destination)
                            <tr class="border-b last:border-b-0">
                                <td class="px-4 py-2 font-medium text-gray-800">
                                    {{ $destination->name ?? $destination->title ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-gray-600">
                                    {{ optional($destination->city)->name ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-gray-600">
                                    {{ $destination->status ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-gray-600">
                                    {{ $destination->created_at?->format('d M Y') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="flex justify-end">
        <a href="{{ route('admin.categories.index') }}" class="btn bg-gray-200 text-gray-800 hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Kategori
        </a>
    </div>
</div>
@endsection
