@extends('layouts.user')

@section('title', 'Riwayat Perjalanan')
@section('page-title', 'Riwayat Perjalanan')
@section('page-subtitle', 'Lihat semua perjalanan dan status pemesanan Anda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-ocean-600"></i>
                Riwayat Perjalanan
            </h1>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-3 rounded-r-lg text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if($bookings->count() === 0)
            <div class="py-10 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>Belum ada perjalanan yang tercatat. Mulai pesan destinasi, hotel, atau restoran pertama Anda!</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Jenis</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Nama</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Tanggal Kunjungan</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Jumlah</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            @php
                                $typeLabel = 'Destinasi';
                                if ($booking->bookable_type === \App\Models\Hotel::class) {
                                    $typeLabel = 'Hotel';
                                } elseif ($booking->bookable_type === \App\Models\Restaurant::class) {
                                    $typeLabel = 'Restoran';
                                }

                                $statusClasses = match($booking->status) {
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <tr>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ $typeLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-gray-900">{{ $booking->bookable->name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    {{ optional($booking->visit_date)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ $booking->quantity }}
                                </td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    @if($booking->status === 'rejected' && $booking->cancellation_reason)
                                        <p class="mt-1 text-xs text-red-600">
                                            Yah, pesananmu ditolak. Alasan mitra: {{ $booking->cancellation_reason }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col gap-2 text-xs">
                                        {{-- Tombol detail selalu ada untuk semua jenis booking --}}
                                        @php
                                            $detailData = [
                                                'code' => $booking->booking_code,
                                                'type' => $typeLabel,
                                                'name' => $booking->bookable->name ?? '-',
                                                'visit_date' => optional($booking->visit_date)->format('d M Y'),
                                                'quantity' => $booking->quantity,
                                                'total_price' => number_format($booking->total_price, 0, ',', '.'),
                                                'final_price' => number_format($booking->final_price, 0, ',', '.'),
                                                'notes' => $booking->notes,
                                                'status' => ucfirst($booking->status),
                                            ];
                                        @endphp
                                        <button type="button"
                                                onclick='openDetailModal(@json($detailData))'
                                                class="px-3 py-1 rounded-lg bg-ocean-50 text-ocean-700 font-semibold hover:bg-ocean-100 text-center border border-ocean-100">
                                            Detail
                                        </button>

                                        @php
                                            $hasReview = \App\Models\Review::where('user_id', auth()->id())
                                                ->where('booking_id', $booking->id)
                                                ->exists();
                                            $canReview = $booking->status === 'completed';
                                        @endphp

                                        @if($hasReview)
                                            <button type="button" class="px-3 py-1 rounded-lg bg-gray-200 text-gray-600 font-semibold cursor-default">
                                                Selesai Review
                                            </button>
                                        @else
                                            <button type="button"
                                                    @if($canReview)
                                                        onclick="openReviewModal({{ $booking->id }}, '{{ addslashes($booking->bookable->name ?? 'Pesanan') }}')"
                                                        class="px-3 py-1 rounded-lg bg-forest-600 text-white font-semibold hover:bg-forest-700 text-center"
                                                    @else
                                                        class="px-3 py-1 rounded-lg bg-gray-200 text-gray-400 font-semibold cursor-not-allowed text-center"
                                                        disabled
                                                    @endif>
                                                Beri Ulasan
                                            </button>
                                            @unless($canReview)
                                                <span class="text-[11px] text-gray-400 mt-1">Menunggu mitra menandai selesai</span>
                                            @endunless
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@push('scripts')
    {{-- Modal Ulasan --}}
    <div id="review-modal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Beri Ulasan</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeReviewModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <p class="text-sm text-gray-600 mb-4" id="review-modal-destination-name"></p>

            <form id="review-form" action="{{ route('user.reviews.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="booking_id" id="review-booking-id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="text-yellow-400" @checked($i === 5)>
                                <span class="text-xs text-gray-600">{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea name="comment" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-ocean-500" placeholder="Boleh dikosongkan, atau ceritakan pengalaman perjalanan Anda"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeReviewModal()" class="px-3 py-1.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg text-sm bg-forest-600 text-white font-semibold hover:bg-forest-700">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Detail Booking --}}
    <div id="detail-modal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Detail Pesanan</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeDetailModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <dl class="text-sm text-gray-700 space-y-2" id="detail-modal-content">
                {{-- konten diisi via JS --}}
            </dl>
        </div>
    </div>

    <script>
        function openReviewModal(bookingId, bookingName) {
            const modal = document.getElementById('review-modal');
            document.getElementById('review-booking-id').value = bookingId;
            document.getElementById('review-modal-destination-name').textContent = 'Untuk: ' + bookingName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReviewModal() {
            const modal = document.getElementById('review-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDetailModal(data) {
            const modal = document.getElementById('detail-modal');
            const container = document.getElementById('detail-modal-content');

            const rows = [
                ['Kode Booking', data.code],
                ['Jenis', data.type],
                ['Nama', data.name],
                ['Tanggal Kunjungan', data.visit_date || '-'],
                ['Jumlah', data.quantity],
                ['Harga Awal', 'Rp ' + data.total_price],
                ['Diskon', 'Rp ' + data.discount_amount],
                ['Total Bayar', 'Rp ' + data.final_price],
                ['Catatan', data.notes || '-'],
                ['Status', data.status],
            ];

            container.innerHTML = rows.map(([label, value]) => `
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">${label}</dt>
                    <dd class="font-medium text-gray-800 text-right">${value}</dd>
                </div>
            `).join('');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detail-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endpush
@endsection
