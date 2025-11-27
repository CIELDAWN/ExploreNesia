@extends('layouts.app')

@section('title', 'Moderasi Ulasan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Moderasi Ulasan</h1>
            <p class="text-gray-600">Kelola ulasan dari pengunjung destinasi Anda</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-600 font-medium">Menunggu Persetujuan</p>
                        <p class="text-3xl font-bold text-yellow-700 mt-2">{{ $stats['pending'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium">Disetujui</p>
                        <p class="text-3xl font-bold text-green-700 mt-2">{{ $stats['approved'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Total Ulasan</p>
                        <p class="text-3xl font-bold text-blue-700 mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <!-- Filter Status -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2"
                            onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Menunggu Persetujuan
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            Disetujui
                        </option>
                    </select>
                </div>

                <!-- Filter Destinasi -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Destinasi</label>
                    <select name="destination_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2"
                            onchange="this.form.submit()">
                        <option value="">Semua Destinasi</option>
                        @foreach($destinations as $dest)
                        <option value="{{ $dest->id }}" {{ request('destination_id') == $dest->id ? 'selected' : '' }}>
                            {{ $dest->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <a href="{{ route('mitra.reviews.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- Reviews List -->
        @if($reviews->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($reviews as $review)
            <div class="bg-white rounded-lg shadow-md p-6 {{ !$review->is_approved ? 'border-l-4 border-yellow-400' : 'border-l-4 border-green-400' }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-grow">
                        <!-- User & Destination Info -->
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $review->user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $review->reviewable->name }}</p>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <!-- Comment -->
                        <p class="text-gray-700 mb-3">{{ $review->comment }}</p>

                        <!-- Images -->
                        @if($review->images && count($review->images) > 0)
                        <div class="flex gap-2 mb-3">
                            @foreach($review->images as $image)
                            <img src="{{ Storage::url($image) }}"
                                 alt="Review image"
                                 class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-80 transition"
                                 onclick="openImageModal('{{ Storage::url($image) }}')">
                            @endforeach
                        </div>
                        @endif

                        <!-- Status Badge -->
                        @if($review->is_approved)
                        <div class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Disetujui
                        </div>
                        @else
                        <div class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Menunggu Persetujuan
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-2 ml-4">
                        @if(!$review->is_approved)
                        <form action="{{ route('mitra.reviews.approve', $review->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Setujui
                            </button>
                        </form>
                        @else
                        <form action="{{ route('mitra.reviews.reject', $review->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batalkan
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('mitra.reviews.destroy', $review->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus ulasan ini? Tindakan tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{ $reviews->links() }}

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Ulasan</h3>
            <p class="text-gray-600">Belum ada ulasan yang perlu dimoderasi</p>
        </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-screen p-4">
        <img id="modalImage" src="" alt="Review image" class="max-w-full max-h-full rounded-lg">
    </div>
</div>

@push('scripts')
<script>
function openImageModal(imageUrl) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageUrl;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush
@endsection
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






