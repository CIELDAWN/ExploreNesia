@extends('layouts.app')

@section('title', 'Buat Ulasan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Buat Ulasan</h1>
            <p class="text-gray-600">Bagikan pengalaman Anda tentang destinasi wisata</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('user.reviews.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Pilih Destinasi -->
                <div class="mb-6">
                    <label for="destination_id" class="block text-gray-700 font-medium mb-2">
                        Pilih Destinasi <span class="text-red-500">*</span>
                    </label>
                    <select name="destination_id"
                            id="destination_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('destination_id') border-red-500 @enderror"
                            required
                            {{ $destination ? 'disabled' : '' }}>
                        <option value="">-- Pilih Destinasi --</option>
                        @foreach($destinations as $dest)
                        <option value="{{ $dest->id }}"
                                {{ (old('destination_id') == $dest->id || ($destination && $destination->id == $dest->id)) ? 'selected' : '' }}>
                            {{ $dest->name }} - {{ $dest->location }}
                        </option>
                        @endforeach
                    </select>
                    @if($destination)
                        <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                    @endif
                    @error('destination_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        Rating <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        @for($i = 5; $i >= 1; $i--)
                        <input type="radio"
                               name="rating"
                               value="{{ $i }}"
                               id="star{{ $i }}"
                               class="hidden peer/star{{ $i }}"
                               {{ old('rating') == $i ? 'checked' : '' }}
                               required>
                        <label for="star{{ $i }}"
                               class="cursor-pointer text-4xl text-gray-300 peer-checked/star{{ $i }}:text-yellow-400 hover:text-yellow-400 transition">
                            ★
                        </label>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Klik bintang untuk memberikan rating</p>
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Komentar -->
                <div class="mb-6">
                    <label for="comment" class="block text-gray-700 font-medium mb-2">
                        Komentar <span class="text-red-500">*</span>
                    </label>
                    <textarea name="comment"
                              id="comment"
                              rows="6"
                              minlength="10"
                              maxlength="1000"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('comment') border-red-500 @enderror"
                              placeholder="Ceritakan pengalaman Anda di destinasi ini... (minimal 10 karakter)"
                              required>{{ old('comment') }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-sm text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</span>
                        <span id="charCount" class="text-sm text-gray-500">0 / 1000</span>
                    </div>
                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Foto -->
                <div class="mb-6">
                    <label for="images" class="block text-gray-700 font-medium mb-2">
                        Foto (Opsional)
                    </label>
                    <input type="file"
                           name="images[]"
                           id="images"
                           accept="image/jpeg,image/png,image/jpg"
                           multiple
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           onchange="previewImages(event)">
                    <p class="text-sm text-gray-500 mt-2">
                        Maksimal 5 foto, format JPG/PNG, ukuran maksimal 2MB per foto
                    </p>
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Preview Images -->
                    <div id="imagePreview" class="grid grid-cols-5 gap-2 mt-4"></div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium mb-1">Perhatian:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Ulasan Anda akan ditinjau oleh mitra destinasi sebelum ditampilkan</li>
                                <li>Berikan ulasan yang jujur dan konstruktif</li>
                                <li>Hindari kata-kata kasar atau tidak pantas</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit"
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-medium transition">
                        Kirim Ulasan
                    </button>
                    <a href="{{ route('user.reviews.index') }}"
                       class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-medium text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Character counter
const commentTextarea = document.getElementById('comment');
const charCount = document.getElementById('charCount');

commentTextarea.addEventListener('input', function() {
    const count = this.value.length;
    charCount.textContent = `${count} / 1000`;

    if (count < 10) {
        charCount.classList.add('text-red-500');
        charCount.classList.remove('text-gray-500');
    } else {
        charCount.classList.remove('text-red-500');
        charCount.classList.add('text-gray-500');
    }
});

// Image preview
function previewImages(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';

    const files = event.target.files;

    if (files.length > 5) {
        alert('Maksimal 5 foto');
        event.target.value = '';
        return;
    }

    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Check file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert(`Foto ${file.name} terlalu besar. Maksimal 2MB per foto.`);
            continue;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    }
}

// Star rating hover effect
document.querySelectorAll('label[for^="star"]').forEach((star, index) => {
    star.addEventListener('mouseenter', function() {
        const rating = 5 - index;
        updateStarDisplay(rating);
    });
});

document.querySelector('.flex.gap-2').addEventListener('mouseleave', function() {
    const checkedStar = document.querySelector('input[name="rating"]:checked');
    if (checkedStar) {
        updateStarDisplay(parseInt(checkedStar.value));
    } else {
        updateStarDisplay(0);
    }
});

function updateStarDisplay(rating) {
    document.querySelectorAll('label[for^="star"]').forEach((star, index) => {
        const starRating = 5 - index;
        if (starRating <= rating) {
            star.classList.add('text-yellow-400');
            star.classList.remove('text-gray-300');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}
</script>
@endpush
@endsection
