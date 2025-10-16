{{-- resources/views/admin/workouts/workoutCreate.blade.php --}}
<x-layouts title="Add Workout">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6" x-data="{
        workoutName: '{{ old('workout_name') }}',
        workoutDesc: '{{ old('description') }}',
        imagePreview: null,
        fileName: '',
        fileError: ''
    }">

        {{-- Title --}}
        <h2
            class="text-2xl font-bold mb-6
                   bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                   bg-clip-text text-transparent border-b pb-3">
            Add Workout
        </h2>

        {{-- Global Validation Errors --}}
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

            {{-- Image Upload --}}
            <div class="pt-4 pb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Workout Image
                </label>

                {{-- Image Preview --}}
                <template x-if="imagePreview">
                    <div class="mb-3">
                        <img :src="imagePreview" class="w-48 h-48 object-cover rounded-lg border shadow">
                        <p class="text-xs text-gray-500 mt-1" x-text="fileName"></p>
                    </div>
                </template>

                {{-- Upload Button --}}
                <label
                    class="flex items-center justify-center px-4 py-2
                           bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                           text-white rounded-lg cursor-pointer
                           hover:scale-105 hover:-translate-y-1 transition transform">
                    📤 Upload Image
                    <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" class="hidden"
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
                                if (!allowed.includes(file.type)) {
                                    fileError = 'Only JPG, JPEG, and PNG files are allowed!';
                                    $event.target.value = '';
                                    fileName = '';
                                    imagePreview = null;
                                    return;
                                }
                                fileError = '';
                                fileName = file.name;
                                const reader = new FileReader();
                                reader.onload = e => imagePreview = e.target.result;
                                reader.readAsDataURL(file);
                            } else {
                                fileError = '';
                                fileName = '';
                                imagePreview = null;
                            }
                        ">
                </label>

                {{-- File Note --}}
                <p class="text-gray-500 text-sm mt-2 italic">
                    Allowed file types: JPG, JPEG, PNG.
                </p>
                <p class="text-red-600 text-sm mt-1" x-text="fileError"></p>
            </div>

            {{-- Workout Name --}}
            <div class="pb-4">
                <label for="workout_name" class="block text-sm font-medium text-gray-700 mb-2">Workout Name</label>
                <input type="text" name="workout_name" id="workout_name" x-model="workoutName"
                    class="w-full rounded-md border border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 p-2"
                    maxlength="20" required>
                <p class="text-gray-500 text-sm mt-1">Characters: <span x-text="workoutName.length"></span>/20</p>
                <p class="text-red-600 text-sm mt-1" x-show="workoutName.length > 20">Maximum 20 characters allowed!</p>
            </div>

            {{-- Description --}}
            <div class="pt-4 pb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" x-model="workoutDesc" maxlength="200"
                    class="w-full rounded-md border border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 p-2">{{ old('description') }}</textarea>
                <p class="text-gray-500 text-sm mt-1">Characters: <span x-text="workoutDesc.length"></span>/200</p>
                <p class="text-red-600 text-sm mt-1" x-show="workoutDesc.length > 200">Maximum 200 characters allowed!
                </p>
            </div>

            {{-- Duration --}}
            <div class="pt-4 pb-4">
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration') }}"
                    class="w-full rounded-md border border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 p-2"
                    min="1">
            </div>

            {{-- Difficulty Level --}}
            <div class="pb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level</label>
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

            {{-- Categories --}}
            <div class="pb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
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

            {{-- Buttons --}}
            <div class="pt-4 flex items-center gap-3">
                <button type="submit" :disabled="workoutName.length > 20 || workoutDesc.length > 200 || fileError"
                    class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                           text-white px-4 py-2 rounded-lg shadow-md
                           hover:scale-105 hover:-translate-y-1 transition transform
                           disabled:opacity-50 disabled:cursor-not-allowed">
                    Save
                </button>
                <a href="{{ route('admin.workouts') }}"
                    class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layouts>
