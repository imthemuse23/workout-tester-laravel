<x-layouts title="Workouts Management">
    <div class="container mx-auto p-6 space-y-6" x-data="{
        openModal: {{ session('show_modal', false) ? 'true' : 'false' }},
        workoutData: @json(old()) || {},
        imagePreview: null,
        fileName: '',
        removeImage: false,
        hasNewImage: false,
        fileError: ''
    }">

        {{-- Title --}}
        <h1
            class="text-2xl font-bold bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                   bg-clip-text text-transparent mb-6">
            Workouts Management
        </h1>

        {{-- Flash Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between bg-green-100 border border-green-300 text-green-800 p-3 rounded-lg shadow-sm">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-green-700 hover:text-green-900 font-bold">✖</button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded-lg shadow-sm mb-4">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.store('modal', {
                        show: true
                    });
                });
            </script>
        @endif

        {{-- Add Button --}}
        <div>
            <a href="{{ route('admin.workouts.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg shadow-md
                       bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white
                       hover:scale-105 hover:-translate-y-1 transition transform">
                ➕ Add Workout
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
                                    <img src="{{ asset('storage/' . $workout->image) }}"
                                        class="w-16 h-16 rounded-lg object-cover">
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
                                {{-- Edit --}}
                                <button
                                    @click="
                                        openModal = true;
                                        workoutData = {{ $workout->toJson() }};
                                        fileName = '';
                                        hasNewImage = false;
                                        removeImage = false;
                                        imagePreview = null;
                                        fileError = '';
                                    "
                                    class="bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-200 text-white px-3 py-1 rounded-md shadow hover:brightness-110 transition">
                                    ✏️
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.workouts.delete', $workout->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this workout?')">
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

        {{-- Modal --}}
        <div x-show="openModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative max-h-[90vh] overflow-y-auto p-6">
                <button @click="openModal = false" class="absolute top-3 right-3 text-gray-500">✖</button>
                <h2 class="text-lg font-bold mb-4">Edit Workout</h2>

                <form :action="`/admin/workouts/${workoutData.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Workout Name --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Workout Name</label>
                        <input type="text" name="workout_name" x-model="workoutData.workout_name"
                            class="w-full border rounded p-2 focus:ring focus:ring-cyan-200" maxlength="20" required>
                        <p class="text-gray-500 text-sm mt-1">Characters:
                            <span x-text="workoutData.workout_name ? workoutData.workout_name.length : 0"></span>/20
                        </p>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" x-model="workoutData.description"
                            class="w-full border rounded p-2 focus:ring focus:ring-cyan-200" maxlength="200" rows="3"></textarea>
                        <p class="text-gray-500 text-sm mt-1">Characters:
                            <span x-text="workoutData.description ? workoutData.description.length : 0"></span>/200
                        </p>
                    </div>


                    {{-- Duration --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                        <input type="number" name="duration"
                            class="w-full border rounded p-2 focus:ring focus:ring-cyan-200"
                            x-model="workoutData.duration" min="1">
                        @error('duration')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Difficulty --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level</label>
                        <div class="flex gap-4">
                            <template x-for="level in ['Beginner','Intermediate','Advanced']" :key="level">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="difficulty" :value="level"
                                        x-model="workoutData.difficulty"
                                        class="border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                    <span class="ml-2" x-text="level"></span>
                                </label>
                            </template>
                        </div>
                        @error('difficulty')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Categories --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($categories as $category)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                        :checked="workoutData.categories?.some(c => c.id === {{ $category->id }})"
                                        class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                    <span class="ml-2">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Image Upload --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Workout Image</label>
                        <template x-if="workoutData.image && !removeImage && !imagePreview && !hasNewImage">
                            <div class="mb-3 relative inline-block">
                                <img :src="'/storage/' + workoutData.image"
                                    class="w-32 h-32 object-cover rounded-lg border shadow">
                                <button type="button"
                                    class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded"
                                    @click="removeImage = true">🗑️</button>
                            </div>
                        </template>

                        <input type="hidden" name="remove_image" :value="removeImage ? 1 : 0">

                        <template x-if="imagePreview">
                            <div class="mb-3 relative w-32 h-32">
                                <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg border shadow">
                                <button type="button"
                                    class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded"
                                    @click="imagePreview = null; fileName = ''; hasNewImage = false; $refs.fileInput.value = '';">✖</button>
                            </div>
                        </template>

                        <label
                            class="flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white rounded-lg cursor-pointer hover:scale-105 hover:-translate-y-1 transition transform">
                            📤 Upload New Image
                            <input type="file" x-ref="fileInput" name="image" accept=".png, .jpg, .jpeg"
                                class="hidden"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
                                        if (!allowed.includes(file.type)) {
                                            fileError = 'Only JPG, JPEG, and PNG files are allowed!';
                                            $event.target.value = '';
                                            return;
                                        }
                                        fileError = '';
                                        fileName = file.name;
                                        hasNewImage = true;
                                        removeImage = false;
                                        const reader = new FileReader();
                                        reader.onload = e => imagePreview = e.target.result;
                                        reader.readAsDataURL(file);
                                    } else {
                                        fileError = '';
                                        fileName = '';
                                        imagePreview = null;
                                        hasNewImage = false;
                                        $event.target.value = '';
                                    }
                                ">
                        </label>
                        <p class="text-red-600 text-sm mt-1" x-text="fileError"></p>
                    </div>

                    {{-- Buttons --}}
                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button"
                            @click="openModal = false; imagePreview = null; fileName = ''; removeImage = false; hasNewImage = false; fileError = '';"
                            class="px-4 py-2 bg-gray-200 rounded shadow hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                                   text-white rounded shadow hover:scale-105 hover:-translate-y-1 transition transform">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts>
