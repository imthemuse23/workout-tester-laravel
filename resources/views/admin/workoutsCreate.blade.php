<x-layouts title="Tambah Workout">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
        {{-- Judul --}}
        <h2
            class="text-2xl font-bold mb-6
                   bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                   bg-clip-text text-transparent border-b pb-3">
            Tambah Workout
        </h2>

        {{-- Tampilkan error validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.workouts.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6 divide-y divide-gray-200">
            @csrf

            {{-- Gambar --}}
            <div class="pt-4 pb-4" x-data="{ imagePreview: null, fileName: '' }">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Workout
                </label>

                {{-- Preview --}}
                <template x-if="imagePreview">
                    <div class="mb-3">
                        <img :src="imagePreview" class="w-48 h-48 object-cover rounded-lg border shadow">
                        <p class="text-xs text-gray-500 mt-1" x-text="fileName"></p>
                    </div>
                </template>

                {{-- Input file custom --}}
                <label
                    class="flex items-center justify-center px-4 py-2
                           bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                           text-white rounded-lg cursor-pointer
                           hover:scale-105 hover:-translate-y-1 transition transform">
                    Pilih Gambar
                    <input type="file" name="image" id="image" accept="image/*" class="hidden"
                        @change="
                               const file = $event.target.files[0];
                               if (file) {
                                   fileName = file.name;
                                   const reader = new FileReader();
                                   reader.onload = e => imagePreview = e.target.result;
                                   reader.readAsDataURL(file);
                               } else {
                                   fileName = '';
                                   imagePreview = null;
                               }
                           ">
                </label>
            </div>

            {{-- Nama Workout --}}
            <div class="pb-4">
                <label for="workout_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Workout</label>
                <input type="text" name="workout_name" id="workout_name" value="{{ old('workout_name') }}"
                    class="w-full rounded-md border border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 p-2"
                    required>
            </div>

            {{-- Deskripsi --}}
            <div class="pt-4 pb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full rounded-md border border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 p-2">{{ old('description') }}</textarea>
            </div>

            {{-- Durasi --}}
            <div class="pt-4 pb-4">
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durasi (menit)</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration') }}"
                    class="w-full rounded-md border border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 p-2"
                    min="1">
            </div>

            {{-- Level Kesulitan --}}
            <div class="pb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Level Kesulitan</label>
                <div class="flex gap-6">
                    @foreach (['Beginner', 'Intermediate', 'Advanced'] as $level)
                        <label class="inline-flex items-center">
                            <input type="radio" name="difficulty" value="{{ $level }}"
                                {{ old('difficulty', $workout->difficulty ?? '') == $level ? 'checked' : '' }}
                                class="border-gray-300 text-cyan-600 focus:ring-cyan-500">
                            <span class="ml-2">{{ $level }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Kategori --}}
            <div class="pb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <div class="flex flex-wrap gap-4">
                    @foreach ($categories as $category)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                {{ in_array($category->id, old('categories', $selectedCategories ?? [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                            <span class="ml-2">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Tombol --}}
            <div class="pt-4 flex items-center gap-3">
                <button type="submit"
                    class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                               text-white px-4 py-2 rounded-lg shadow-md
                               hover:scale-105 hover:-translate-y-1 transition transform">
                    Simpan
                </button>
                <a href="{{ route('admin.workouts') }}"
                    class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts>
