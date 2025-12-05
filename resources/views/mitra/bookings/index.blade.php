@extends('layouts.mitra')

@section('title', 'Pemesanan')
@section('page-title', 'Pemesanan')
@section('page-subtitle', 'Daftar pesanan yang masuk ke bisnis Anda')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <i class="fas fa-calendar-check text-ocean-600"></i>
            Pesanan Terbaru
        </h2>
    </div>

    @if($bookings->count() === 0)
        <div class="py-10 text-center text-gray-500">
            <i class="fas fa-inbox text-4xl mb-3"></i>
            <p>Belum ada pesanan untuk bisnis Anda.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Kode</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Pelanggan</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Destinasi</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Tgl Kunjungan</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Jumlah</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Total</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Aksi</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="px-4 py-2 font-mono text-xs text-gray-700">{{ $booking->booking_code }}</td>
                            <td class="px-4 py-2">
                                <div class="font-medium text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="font-medium text-gray-900">{{ $booking->bookable->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-2">
                                {{ optional($booking->visit_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $booking->quantity }}</td>
                            <td class="px-4 py-2">
                                Rp {{ number_format($booking->final_price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $statusClasses = match($booking->status) {
                                        'confirmed' => 'bg-green-100 text-green-700',
                                        'completed' => 'bg-blue-100 text-blue-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default => 'bg-yellow-100 text-yellow-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                    {{ ucfirst($booking->status) }}
                                </span>

                                @if($booking->status === 'rejected' && $booking->cancellation_reason)
                                    <p class="mt-1 text-xs text-red-600">Alasan: {{ $booking->cancellation_reason }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($booking->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('mitra.bookings.confirm', $booking) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded-lg text-xs font-semibold bg-green-500 text-white hover:bg-green-600">
                                                <i class="fas fa-check mr-1"></i>Terima
                                            </button>
                                        </form>

                                        <form action="{{ route('mitra.bookings.reject', $booking) }}" method="POST" onsubmit="return confirmReject(this);">
                                            @csrf
                                            <input type="hidden" name="reason" value="">
                                            <button type="submit" class="px-3 py-1 rounded-lg text-xs font-semibold bg-red-500 text-white hover:bg-red-600">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        </form>
                                    </div>
                                @elseif($booking->status === 'confirmed')
                                    <form action="{{ route('mitra.bookings.complete', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-500 text-white hover:bg-blue-600">
                                            <i class="fas fa-flag-checkered mr-1"></i>Tandai Selesai
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada aksi</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-xs text-gray-500">
                                {{ $booking->created_at->diffForHumans() }}
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
@endsection
@push('scripts')
<script>
    function confirmReject(form) {
        const reason = prompt('Masukkan alasan penolakan pesanan:');
        if (!reason) {
            return false;
        }
        form.querySelector('input[name="reason"]').value = reason;
        return true;
    }
</script>
@endpush
