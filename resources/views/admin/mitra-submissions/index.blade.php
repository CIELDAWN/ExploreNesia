@extends('layouts.admin')

@section('title', 'Manajemen Mitra')
@section('page-title', 'Manajemen Mitra')
@section('page-subtitle', 'Kelola semua pengajuan bisnis mitra (Destinasi, Hotel, Restoran, Bisnis Mandiri)')

@section('content')

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-list text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Pengajuan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Disetujui</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Ditolak</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-ocean-100 flex items-center justify-center">
            <i class="fas fa-map-marked-alt text-ocean-600"></i>
        </div>
        <div>
            <p class="text-sm text-gray-600">Destinasi</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['destinations'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-forest-100 flex items-center justify-center">
            <i class="fas fa-hotel text-forest-600"></i>
        </div>
        <div>
            <p class="text-sm text-gray-600">Hotel</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['hotels'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-earth-100 flex items-center justify-center">
            <i class="fas fa-utensils text-earth-600"></i>
        </div>
        <div>
            <p class="text-sm text-gray-600">Restoran</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['restaurants'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
            <i class="fas fa-briefcase text-blue-600"></i>
        </div>
        <div>
            <p class="text-sm text-gray-600">Bisnis Mitra</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['mitras'] }}</p>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- Header with Filters -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Daftar Pengajuan Mitra</h2>
                <p class="text-sm text-gray-600 mt-1">Filter dan kelola pengajuan dari mitra</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-4 flex flex-wrap gap-4">
            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                <div class="flex gap-2">
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => 'all', 'status' => $status]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $type == 'all' ? 'bg-ocean-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-th mr-2"></i>Semua
                    </a>
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => 'destination', 'status' => $status]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $type == 'destination' ? 'bg-ocean-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-map-marked-alt mr-2"></i>Destinasi
                    </a>
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => 'hotel', 'status' => $status]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $type == 'hotel' ? 'bg-forest-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-hotel mr-2"></i>Hotel
                    </a>
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => 'restaurant', 'status' => $status]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $type == 'restaurant' ? 'bg-earth-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-utensils mr-2"></i>Restoran
                    </a>
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => 'mitra', 'status' => $status]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $type == 'mitra' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-briefcase mr-2"></i>Mitra
                    </a>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex gap-2">
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => $type, 'status' => 'all']) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $status == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => $type, 'status' => 'pending']) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $status == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => $type, 'status' => 'approved']) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $status == 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Disetujui
                    </a>
                    <a href="{{ route('admin.mitra-submissions.index', ['type' => $type, 'status' => 'rejected']) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition {{ $status == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Ditolak
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="divide-y divide-gray-200">
        @forelse($submissions as $submission)
        <div class="p-6 hover:bg-gray-50 transition">
            <div class="flex items-start gap-4">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-lg bg-{{ $submission->type_color }}-100 flex items-center justify-center">
                        <i class="fas {{ $submission->type_icon }} text-{{ $submission->type_color }}-600 text-2xl"></i>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $submission->name }}</h3>
                                <span class="px-3 py-1 bg-{{ $submission->type_color }}-100 text-{{ $submission->type_color }}-700 text-xs font-semibold rounded-full">
                                    {{ $submission->type_label }}
                                </span>
                                @if($submission->status == 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                    <i class="fas fa-clock mr-1"></i>Menunggu
                                </span>
                                @elseif($submission->status == 'approved')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Disetujui
                                </span>
                                @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                <span><i class="fas fa-user mr-2"></i>{{ $submission->user->name }}</span>
                                <span><i class="fas fa-map-marker-alt mr-2"></i>{{ $submission->city->name ?? 'N/A' }}</span>
                                <span><i class="fas fa-calendar mr-2"></i>{{ $submission->created_at->format('d M Y') }}</span>
                            </div>

                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                {{ Str::limit($submission->description, 150) }}
                            </p>

                            @if($submission->status == 'rejected' && $submission->rejection_reason)
                            <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 rounded-r">
                                <p class="text-sm text-red-800">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <strong>Alasan Penolakan:</strong> {{ $submission->rejection_reason }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 mt-4">
                        @if($submission->status == 'pending')
                        <!-- Approve Button -->
                        <form action="{{ route('admin.mitra-submissions.approve') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="type" value="{{ $submission->submission_type }}">
                            <input type="hidden" name="id" value="{{ $submission->id }}">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition" onclick="return confirm('Setujui pengajuan ini?')">
                                <i class="fas fa-check mr-2"></i>Setujui
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <button onclick="showRejectModal('{{ $submission->submission_type }}', '{{ $submission->id }}', '{{ $submission->name }}')" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                            <i class="fas fa-times mr-2"></i>Tolak
                        </button>
                        @endif

                        <!-- View Detail -->
                        <a href="{{ route('admin.mitra-submissions.show', $submission->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('admin.mitra-submissions.destroy') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="type" value="{{ $submission->submission_type }}">
                            <input type="hidden" name="id" value="{{ $submission->id }}">
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-semibold hover:bg-gray-700 transition" onclick="return confirm('Hapus pengajuan ini?')">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Pengajuan</h3>
            <p class="text-gray-600">Belum ada pengajuan dari mitra untuk kategori dan status yang dipilih</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Tolak Pengajuan</h3>
            <p class="text-sm text-gray-600 mt-1" id="rejectModalSubtitle"></p>
        </div>
        <form action="{{ route('admin.mitra-submissions.reject') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="type" id="rejectType">
            <input type="hidden" name="id" id="rejectId">
            
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" 
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-times mr-2"></i>Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showRejectModal(type, id, name) {
    document.getElementById('rejectType').value = type;
    document.getElementById('rejectId').value = id;
    document.getElementById('rejectModalSubtitle').textContent = name;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('reason').value = '';
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endpush