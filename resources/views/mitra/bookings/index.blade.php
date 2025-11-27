@extends('layouts.mitra')

@section('title', 'Pemesanan')
@section('page-title', 'Pemesanan Pengunjung')
@section('page-subtitle', 'Pantau status pesanan dan respon pelanggan')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="input-control" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending','confirmed','completed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pengunjung</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Produk</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($bookings as $booking)
            <tr>
                <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $booking->booking_code }}</td>
                <td class="px-4 py-4 text-sm text-gray-700">
                    <p>{{ $booking->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                </td>
                <td class="px-4 py-4 text-sm text-gray-700">
                    <p class="font-medium">{{ $booking->bookable->name ?? 'Tidak diketahui' }}</p>
                    <p class="text-xs text-gray-500">{{ class_basename($booking->bookable_type) }}</p>
                </td>
                <td class="px-4 py-4 text-sm text-gray-700">
                    <p>Kunjungan: {{ optional($booking->visit_date)->format('d M Y') ?? '-' }}</p>
                    <p class="text-xs text-gray-500">Dibuat: {{ $booking->created_at->format('d M Y H:i') }}</p>
                </td>
                <td class="px-4 py-4 text-sm text-gray-900 font-semibold">
                    Rp {{ number_format($booking->final_price, 0, ',', '.') }}
                </td>
                <td class="px-4 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @class([
                            'bg-orange-100 text-orange-700' => $booking->status === 'pending',
                            'bg-green-100 text-green-700' => $booking->status === 'confirmed' || $booking->status === 'completed',
                            'bg-red-100 text-red-700' => $booking->status === 'cancelled',
                        ])">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td class="px-4 py-4 text-right text-sm">
                    <form action="{{ route('mitra.bookings.update-status', $booking) }}" method="POST" class="inline-flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border-gray-300 rounded-lg text-sm">
                            @foreach(['pending','confirmed','completed','cancelled'] as $status)
                                <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="cancellation_reason" placeholder="Alasan batal" class="border-gray-200 rounded-lg text-sm px-2 py-1"
                            @disabled($booking->status !== 'cancelled')>
                        <button class="px-3 py-1.5 rounded-lg bg-ocean-600 text-white hover:bg-ocean-700 text-xs">Update</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    Belum ada pemesanan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $bookings->links() }}
</div>
@endsection






