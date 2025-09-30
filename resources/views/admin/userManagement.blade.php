<x-layouts title="User Management">
    <div class="container mx-auto p-6">
        <!-- Judul -->
        <h1
            class="text-2xl font-extrabold
                bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200
                bg-clip-text text-transparent mb-6">
            User Management
        </h1>

        <!-- Alert Success -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between bg-green-100 text-green-800 p-3 rounded mb-4">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-green-900 hover:text-green-700 font-bold">
                    &times;
                </button>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="bg-gray-50 hover:bg-gray-100 transition">
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="px-3 py-1 rounded-lg bg-red-500 text-white
                                            hover:bg-red-600 shadow transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada user
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts>
