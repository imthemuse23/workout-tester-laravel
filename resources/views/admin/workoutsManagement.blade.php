{{-- resources/views/admin/workouts/index.blade.php --}}
<x-layouts title="Workouts Management">
    <div class="container mx-auto p-6 space-y-6" x-data="{
        openModal: false,
        workoutData: {},
        imagePreview: null,
        fileName: '',
        removeImage: false,
        hasNewImage: false
    }">

        {{-- Judul --}}
        <h1
            class="text-2xl font-bold
                   bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                   bg-clip-text text-transparent mb-6">
            Workouts Management
        </h1>

        {{-- Flash Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between bg-green-100 border border-green-300 text-green-800 p-3 rounded-lg shadow-sm">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-green-700 hover:text-green-900 font-bold">
                    ✖
                </button>
            </div>
        @endif

        {{-- Button Tambah --}}
        <div>
            <a href="{{ route('admin.workouts.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg shadow-md
                       bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white
                       hover:scale-105 hover:-translate-y-1 transition transform">
                ➕ Tambah Workout
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">Image</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Duration</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Level</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($workouts as $workout)
                        <tr>
                            <td class="px-6 py-3">
                                @if ($workout->image)
                                    <img src="{{ asset('storage/' . $workout->image) }}" class="w-16 h-16 rounded-lg">
                                @else
                                    <span class="text-gray-400 italic">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-semibold">{{ $workout->workout_name }}</td>
                            <td class="px-6 py-3 truncate max-w-xs">{{ $workout->description }}</td>
                            <td class="px-6 py-3">{{ $workout->duration }} min</td>
                            <td class="px-6 py-3">
                                @if ($workout->categories->isNotEmpty())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($workout->categories as $category)
                                            <span class="bg-gray-100 px-2 py-1 rounded-md text-xs">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 capitalize">{{ $workout->difficulty }}</td>
                            <td class="px-6 py-8 flex justify-center gap-2">
                                <button
                                    @click="
                                        openModal = true;
                                        workoutData = {{ $workout->toJson() }};
                                        fileName = '';
                                        hasNewImage = false;
                                        removeImage = false;
                                        imagePreview = null;
                                        if ($refs.fileInput) $refs.fileInput.value = '';
                                    "
                                    class="bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-200 text-white px-3 py-1 rounded-md shadow hover:brightness-110 transition">
                                    ✏️
                                </button>
                                <form action="{{ route('admin.workouts.delete', $workout->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus workout ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-gradient-to-r from-red-500 via-red-400 to-red-600 text-white px-3 py-1 rounded-md shadow hover:brightness-110 transition">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modal Edit --}}
        <div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative max-h-[90vh] overflow-y-auto p-6">
                {{-- Tombol close --}}
                <button @click="openModal = false" class="absolute top-3 right-3 text-gray-500">✖</button>

                <h2 class="text-lg font-bold mb-4">Edit Workout</h2>

                <form :action="`/admin/workouts/${workoutData.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Name</label>
                        <input type="text" name="workout_name" class="w-full border rounded p-2"
                            x-model="workoutData.workout_name">
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="w-full border rounded p-2" rows="3" x-text="workoutData.description"></textarea>
                    </div>

                    {{-- Duration --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Duration (min)</label>
                        <input type="number" name="duration" class="w-full border rounded p-2"
                            x-model="workoutData.duration">
                    </div>

                    {{-- Level --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Level</label>
                        <select name="difficulty" class="w-full border rounded p-2" x-model="workoutData.difficulty">
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>

                    {{-- Categories --}}
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($categories as $category)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    :checked="workoutData.categories.some(c => c.id === {{ $category->id }})"
                                    class="rounded border-gray-300 text-blue-600 focus:ring focus:ring-blue-200">
                                <span class="text-sm text-gray-700">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Image --}}
                    <div class="mb-3">
                        <!-- Gambar lama -->
                        <template x-if="workoutData.image && !removeImage && !imagePreview && !hasNewImage">
                            <div class="mb-3 relative inline-block">
                                <img :src="'/storage/' + workoutData.image"
                                    class="w-32 h-32 object-cover rounded-lg border shadow">
                                <button type="button"
                                    class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded"
                                    @click="removeImage = true">
                                    🗑️
                                </button>
                            </div>
                        </template>

                        <!-- Hidden input hapus gambar -->
                        <input type="hidden" name="remove_image" :value="removeImage ? 1 : 0">

                        <!-- Preview gambar baru -->
                        <template x-if="imagePreview">
                            <div class="mb-3 relative w-32 h-32">
                                <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg border shadow">
                                <button type="button"
                                    class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded"
                                    @click="
                                        imagePreview = null;
                                        fileName = '';
                                        hasNewImage = false;
                                        $refs.fileInput.value = '';
                                    ">
                                    ✖
                                </button>
                            </div>
                        </template>

                        <!-- Tombol pilih file -->
                        <label
                            class="flex items-center justify-center px-4 py-2
                                   bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                                   text-white rounded-lg cursor-pointer
                                   hover:scale-105 hover:-translate-y-1 transition transform">
                            Pilih Gambar Baru
                            <input type="file" x-ref="fileInput" name="image" accept="image/*" class="hidden"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        fileName = file.name;
                                        hasNewImage = true;
                                        removeImage = false;
                                        const reader = new FileReader();
                                        reader.onload = e => imagePreview = e.target.result;
                                        reader.readAsDataURL(file);
                                    } else {
                                        fileName = '';
                                        imagePreview = null;
                                        hasNewImage = false;
                                        $event.target.value = '';
                                    }
                                ">
                        </label>
                    </div>

                    {{-- tombol submit --}}
                    <div class="mt-4 flex justify-end gap-2 bottom-0 bg-transparent py-2">
                        <button type="button"
                            @click="
                                openModal = false;
                                imagePreview = null;
                                fileName = '';
                                hasNewImage = false;
                                removeImage = false;
                                if ($refs.fileInput) $refs.fileInput.value = '';
                            "
                            class="px-4 py-2 bg-gray-300 rounded shadow hover:brightness-110 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2
                                       bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                                       text-white rounded shadow hover:scale-105 hover:-translate-y-1 transition transform">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts>
