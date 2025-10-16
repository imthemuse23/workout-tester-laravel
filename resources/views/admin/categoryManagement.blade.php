<x-layouts title="Workout Categories">
    <div class="container mx-auto p-6 space-y-6" x-data="{
        openModal: false,
        selectedId: null,
        selectedName: '',
        selectedDesc: '',
        newName: '',
        newDesc: ''
    }" x-init="newName = '{{ old('name') }}';
    newDesc = '{{ old('description') }}';
    @if ($errors->any() && old('edit_id')) openModal = true;
                selectedId = {{ old('edit_id') }};
                selectedName = '{{ old('name') }}';
                selectedDesc = '{{ old('description') }}'; @endif">

        {{-- Title --}}
        <h1
            class="text-2xl font-bold mb-4 bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 bg-clip-text text-transparent">
            Workout Categories
        </h1>

        {{-- Success Notification --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow mb-4 flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-700 hover:text-green-900 font-bold">✖</button>
            </div>
        @endif

        {{-- Error Alert (global) --}}
        @if ($errors->any() && !old('edit_id'))
            <div class="border border-red-300 bg-red-50 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Add Category Form --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-cyan-700 border-b pb-2">Add Category</h2>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" name="name" id="name" maxlength="20" x-model="newName"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none"
                        required>
                    <p class="text-gray-500 text-sm mt-1">Characters: <span x-text="newName.length"></span>/20</p>
                    <p class="text-red-600 text-sm mt-1" x-show="newName.length > 20">Maximum 20 characters allowed!</p>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description
                        (optional)</label>
                    <textarea name="description" id="description" rows="3" maxlength="200" x-model="newDesc"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none"></textarea>
                    <p class="text-gray-500 text-sm mt-1">Characters: <span x-text="newDesc.length"></span>/200</p>
                    <p class="text-red-600 text-sm mt-1" x-show="newDesc.length > 200">Maximum 200 characters allowed!
                    </p>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" :disabled="newName.length > 20 || newDesc.length > 200"
                        class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white px-4 py-2 rounded-lg shadow-md hover:scale-105 hover:-translate-y-1 transition transform disabled:opacity-50 disabled:cursor-not-allowed">
                        Add
                    </button>
                    <button type="reset" @click="newName=''; newDesc='';"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        {{-- Category List --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-cyan-700 border-b pb-2">Category List</h2>

            @if ($categories->isEmpty())
                <p class="text-sm text-gray-500">No categories yet. Add a category above.</p>
            @else
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-600">Name</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-600">Description</th>
                                <th class="px-6 py-3 text-center font-medium text-gray-600">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $category->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $category->description ?? '-' }}</td>
                                    <td class="px-6 py-3 text-center space-x-2">
                                        {{-- Edit Button --}}
                                        <button
                                            @click="openModal = true; selectedId = {{ $category->id }}; selectedName = '{{ $category->name }}'; selectedDesc = '{{ $category->description }}'"
                                            class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md shadow-sm transition transform hover:scale-105">
                                            ✏️
                                        </button>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.categories.delete', $category->id) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md shadow-sm transition transform hover:scale-105">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Edit Category Modal --}}
        <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            style="display:none;">
            <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg" @click.away="openModal = false">
                <h2 class="text-lg font-semibold mb-4 text-cyan-700 border-b pb-2">Edit Category</h2>

                {{-- Error Alert (inside modal) --}}
                @if ($errors->any() && old('edit_id'))
                    <div class="border border-red-300 bg-red-50 text-red-700 px-4 py-3 rounded-lg mb-4">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" :action="'/admin/categories/' + selectedId">
                    @csrf
                    @method('PUT')

                    {{-- Hidden input for edit id --}}
                    <input type="hidden" name="edit_id" :value="selectedId">

                    {{-- Name --}}
                    <div class="mb-4">
                        <label class="block text-gray-700">Name</label>
                        <input type="text" name="name" x-model="selectedName" maxlength="20"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none">
                        <p class="text-gray-500 text-sm mt-1">Characters: <span x-text="selectedName.length"></span>/20
                        </p>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="block text-gray-700">Description</label>
                        <textarea name="description" x-model="selectedDesc" maxlength="200"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none"></textarea>
                        <p class="text-gray-500 text-sm mt-1">Characters: <span x-text="selectedDesc.length"></span>/200
                        </p>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openModal = false"
                            class="bg-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="selectedName.length > 20 || selectedDesc.length > 200"
                            class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white px-4 py-2 rounded-md shadow-md hover:scale-105 hover:-translate-y-1 transition transform disabled:opacity-50 disabled:cursor-not-allowed">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts>
