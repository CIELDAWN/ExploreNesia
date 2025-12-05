@extends('layouts.mitra')

@section('title', 'Ulasan')
@section('page-title', 'Ulasan Pengguna')
@section('page-subtitle', 'Lihat feedback dari pengguna tentang bisnis Anda')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <i class="fas fa-star text-yellow-400"></i>
            Ulasan Terbaru
        </h2>
    </div>

    @if($reviews->count() === 0)
        <div class="py-10 text-center text-gray-500">
            <i class="fas fa-comments text-4xl mb-3"></i>
            <p>Belum ada ulasan untuk bisnis Anda.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="border border-gray-100 rounded-xl p-4 flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-ocean-50 flex items-center justify-center text-ocean-700 font-semibold text-sm">
                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $review->user->name ?? 'Pengguna' }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($review->booking && $review->booking->bookable)
                                        {{ $review->business_name ?? $review->booking->bookable->name }}
                                    @else
                                        {{ $review->business_name ?? '-' }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                @endfor
                                <span class="ml-1 text-sm font-semibold text-gray-800">{{ $review->rating }}/5</span>
                            </div>
                        </div>

                        @if($review->comment)
                            <p class="text-sm text-gray-700 mt-1">{{ $review->comment }}</p>
                        @else
                            <p class="text-xs text-gray-400 mt-1">Pengguna tidak meninggalkan catatan.</p>
                        @endif

                        <div class="mt-2 flex items-center justify-between text-xs text-gray-400">
                            <span>
                                Dipesan pada:
                                @if($review->booking && $review->booking->visit_date)
                                    {{ optional($review->booking->visit_date)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </span>
                            <span>{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
