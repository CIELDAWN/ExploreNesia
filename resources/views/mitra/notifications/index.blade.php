@extends('layouts.mitra')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Dapatkan pemberitahuan terbaru tentang pesanan dan ulasan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">Kotak Notifikasi</h3>
        <p class="text-sm text-gray-500">Semua pemberitahuan baru akan muncul di sini.</p>
    </div>
    <form action="{{ route('mitra.notifications.read-all') }}" method="POST">
        @csrf
        <button class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
            <i class="fas fa-check-double mr-2"></i> Tandai semua telah dibaca
        </button>
    </form>
</div>

<div class="space-y-4">
    @forelse($notifications as $notification)
    <div class="bg-white rounded-xl border {{ $notification->read_at ? 'border-gray-100' : 'border-ocean-200' }} shadow-sm p-5 flex justify-between items-start">
        <div>
            <p class="text-sm font-semibold text-gray-900">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
            <p class="text-sm text-gray-700 mt-1">{{ $notification->data['message'] ?? '-' }}</p>
            @if(isset($notification->data['meta']))
                <p class="text-xs text-gray-500 mt-2">{{ $notification->data['meta'] }}</p>
            @endif
            <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
        </div>
        @if(!$notification->read_at)
        <form action="{{ route('mitra.notifications.read', $notification) }}" method="POST">
            @csrf
            <button class="px-3 py-1.5 rounded-lg bg-ocean-600 text-white text-xs hover:bg-ocean-700">Tandai Dibaca</button>
        </form>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm p-6 text-center text-gray-500">
        Belum ada notifikasi.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $notifications->links() }}
</div>
@endsection






