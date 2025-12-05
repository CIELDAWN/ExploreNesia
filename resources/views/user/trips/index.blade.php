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
                                        @if($booking->status === 'confirmed' && $booking->visit_date && $booking->visit_date->isPast())
                                            <form action="{{ route('user.trips.complete', $booking) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 rounded-lg bg-ocean-600 text-white font-semibold hover:bg-ocean-700">
                                                    Tandai Selesai
                                                </button>
                                            </form>
                                        @endif

                                        @if($booking->status === 'completed' && $booking->bookable_type === \App\Models\Destination::class)
                                            @php
                                                $hasReview = \App\Models\Review::where('user_id', auth()->id())
                                                    ->where('destination_id', $booking->bookable_id)
                                                    ->exists();
                                            @endphp
                                            @if(! $hasReview)
                                                <a href="{{ route('user.reviews.create', ['destination_id' => $booking->bookable_id]) }}"
                                                   class="px-3 py-1 rounded-lg bg-forest-600 text-white font-semibold hover:bg-forest-700 text-center">
                                                    Beri Ulasan
                                                </a>
                                            @else
                                                <span class="text-gray-400">Ulasan sudah dibuat</span>
                                            @endif
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
@endsection
