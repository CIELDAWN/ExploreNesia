@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')
@section('page-subtitle', 'Buat kategori destinasi wisata baru')

@section('content')

<div class="max-w-4xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Kategori
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Informasi Kategori</h2>
            <p class="text-sm text-gray-600 mt-1">Isi form di bawah untuk menambahkan kategori baru</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Nama Kategori -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Wisata Alam"
                    required
                >
                @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icon Emoji -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                    Icon Emoji <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="icon" 
                    id="icon" 
                    value="{{ old('icon') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent @error('icon') border-red-500 @enderror"
                    placeholder="🏞️"
                    maxlength="10"
                    required
                >
                <p class="mt-2 text-sm text-gray-500">
                    Gunakan emoji untuk mewakili kategori. 
                    <a href="https://emojipedia.org" target="_blank" class="text-ocean-600 hover:underline">Lihat daftar emoji</a>
                </p>
                @error('icon')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-500 focus:border-transparent @error('description') border-red-500 @enderror"
                    placeholder="Jelaskan tentang kategori ini..."
                >{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview -->
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-3">Preview:</p>
                <div class="flex items-center gap-3">
                    <span id="preview-icon" class="text-3xl">🏞️</span>
                    <span id="preview-name" class="text-lg font-semibold text-gray-900">Wisata Alam</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-ocean-600 text-white rounded-lg font-semibold hover:bg-ocean-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live preview
    document.getElementById('name').addEventListener('input', function() {
        document.getElementById('preview-name').textContent = this.value || 'Wisata Alam';
    });

    document.getElementById('icon').addEventListener('input', function() {
        document.getElementById('preview-icon').textContent = this.value || '🏞️';
    });
</script>
@endpush