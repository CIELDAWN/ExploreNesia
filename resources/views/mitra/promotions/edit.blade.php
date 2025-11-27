@extends('layouts.mitra')

@section('title', 'Edit Promo')
@section('page-title', 'Perbarui Promo')
@section('page-subtitle', 'Perbarui detail promo yang sedang berjalan')

@section('content')
@php
    $defaultType = strtolower(class_basename($promotion->promotionable_type));
@endphp

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('mitra.promotions.update', $promotion) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten</label>
                <select name="promotionable_type" class="input-control" id="promotion-type" required>
                    <option value="destination" @selected(old('promotionable_type', $defaultType) === 'destination')>Destinasi</option>
                    <option value="hotel" @selected(old('promotionable_type', $defaultType) === 'hotel')>Hotel</option>
                    <option value="restaurant" @selected(old('promotionable_type', $defaultType) === 'restaurant')>Restoran</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Konten</label>
                <select name="promotionable_id" class="input-control" id="promotion-target" required>
                    @foreach($destinations as $destination)
                        <option data-type="destination" value="{{ $destination->id }}" @selected(old('promotionable_id', $promotion->promotionable_id) == $destination->id && $defaultType === 'destination')>
                            Destinasi - {{ $destination->name }}
                        </option>
                    @endforeach
                    @foreach($hotels as $hotel)
                        <option data-type="hotel" value="{{ $hotel->id }}" @selected(old('promotionable_id', $promotion->promotionable_id) == $hotel->id && $defaultType === 'hotel')>
                            Hotel - {{ $hotel->name }}
                        </option>
                    @endforeach
                    @foreach($restaurants as $restaurant)
                        <option data-type="restaurant" value="{{ $restaurant->id }}" @selected(old('promotionable_id', $promotion->promotionable_id) == $restaurant->id && $defaultType === 'restaurant')>
                            Restoran - {{ $restaurant->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Promo <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $promotion->title) }}" required class="input-control">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" rows="3" required class="input-control">{{ old('description', $promotion->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Diskon</label>
                <select name="discount_type" class="input-control" required>
                    <option value="percentage" @selected(old('discount_type', $promotion->discount_type) === 'percentage')>Persentase (%)</option>
                    <option value="fixed" @selected(old('discount_type', $promotion->discount_type) === 'fixed')>Nominal (Rp)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Diskon</label>
                <input type="number" name="discount_value" value="{{ old('discount_value', $promotion->discount_value) }}" step="0.01" required class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimal Transaksi</label>
                <input type="number" name="min_transaction" value="{{ old('min_transaction', $promotion->min_transaction) }}" step="0.01" class="input-control">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" required class="input-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" required class="input-control">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batas Penggunaan</label>
                <input type="number" name="max_usage" value="{{ old('max_usage', $promotion->max_usage) }}" class="input-control">
            </div>
            <div class="flex items-center gap-2 mt-6">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promotion->is_active)) class="rounded border-gray-300 text-ocean-600 focus:ring-ocean-500">
                <label class="text-sm text-gray-700">Promo aktif</label>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('mitra.promotions.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Kembali</a>
            <button class="px-6 py-2 rounded-lg bg-ocean-600 text-white hover:bg-ocean-700">Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const typeSelect = document.getElementById('promotion-type');
    const targetSelect = document.getElementById('promotion-target');

    function filterOptions() {
        const type = typeSelect.value;
        Array.from(targetSelect.options).forEach(option => {
            if (!option.dataset.type) return;
            option.hidden = type && option.dataset.type !== type;
        });
    }

    typeSelect.addEventListener('change', filterOptions);
    filterOptions();
</script>
@endpush
@endsection

