@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-sm p-6 max-w-xl">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Edit Kategori</h1>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="input-control" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="input-control">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="input-control" placeholder="Contoh: 📍" required>
                <p class="text-xs text-gray-500 mt-1">Gunakan emoji singkat untuk ditampilkan di badge dan chip kategori.</p>
            </div>

            <div class="flex justify-between items-center pt-4 border-t mt-4">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2 rounded-lg bg-ocean-600 text-white hover:bg-ocean-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
