<x-layouts title="Workout Categories">
    <div class="container mx-auto p-6 space-y-6" x-data="{ openModal: false, selectedId: null, selectedName: '', selectedDesc: '' }">

        {{-- Judul --}}
        <h1
            class="text-2xl font-bold mb-4
                   bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                   bg-clip-text text-transparent">
            Workout Categories
        </h1>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow mb-4 flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="$el.parentElement.remove()"
                    class="text-green-700 hover:text-green-900 font-bold">✖</button>
            </div>
        @endif

        {{-- Form tambah kategori --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-cyan-700 border-b pb-2">Tambah Kategori</h2>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="name" id="name"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (opsional)</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white px-4 py-2 rounded-lg shadow-md hover:scale-105 hover:-translate-y-1 transition transform">
                        Tambah
                    </button>

                    <button type="reset"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        {{-- Daftar kategori --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-cyan-700 border-b pb-2">Daftar Kategori</h2>

            @if ($categories->isEmpty())
                <p class="text-sm text-gray-500">Belum ada kategori. Tambahkan kategori di atas.</p>
            @else
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-600">Nama</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-600">Deskripsi</th>
                                <th class="px-6 py-3 text-center font-medium text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $category->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $category->description ?? '-' }}</td>
                                    <td class="px-6 py-3 text-center space-x-2">
                                        {{-- Tombol Edit --}}
                                        <button
                                            @click="openModal = true; selectedId = {{ $category->id }}; selectedName = '{{ $category->name }}'; selectedDesc = '{{ $category->description }}'"
                                            class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md shadow-sm transition transform hover:scale-105">
                                            ✏️
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('admin.categories.delete', $category->id) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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

        {{-- Modal Edit Category --}}
        <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            style="display:none;">
            <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg" @click.away="openModal = false">
                <h2 class="text-lg font-semibold mb-4 text-cyan-700 border-b pb-2">Edit Kategori</h2>

                <form method="POST" :action="'/admin/categories/' + selectedId">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Nama</label>
                        <input type="text" name="name" x-model="selectedName"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Deskripsi</label>
                        <textarea name="description" x-model="selectedDesc"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:outline-none"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openModal = false"
                            class="bg-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 text-white px-4 py-2 rounded-md shadow-md hover:scale-105 hover:-translate-y-1 transition transform">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts>
