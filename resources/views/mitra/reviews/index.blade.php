@extends('layouts.mitra')

@section('title', 'Ulasan Pengunjung')
@section('page-title', 'Ulasan & Rating')
@section('page-subtitle', 'Pantau pengalaman pengunjung dan tanggapi masukan mereka')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Filter Rating</label>
            <select name="rating" class="input-control" onchange="this.form.submit()">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} Bintang</option>
                @endfor
            </select>
        </div>
    </form>
</div>

<div class="space-y-4">
    @forelse($reviews as $review)
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $review->user->name }}</p>
                <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex items-center gap-1">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                @endfor
            </div>
        </div>

        <div class="mt-3">
            <p class="text-sm text-gray-700">{{ $review->comment }}</p>
        </div>

        <div class="mt-3 text-xs text-gray-500 flex items-center gap-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>{{ $review->reviewable->name ?? 'Konten dihapus' }} ({{ class_basename($review->reviewable_type) }})</span>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm p-6 text-center text-gray-500">
        Belum ada ulasan.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $reviews->links() }}
</div>
@endsection






