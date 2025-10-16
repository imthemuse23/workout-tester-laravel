<x-layouts title="Profile">
    <div class="container mx-auto px-4 py-8">

        {{-- Header Profil --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">Profile</h1>
        </div>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg flex justify-between items-center shadow">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-green-700 font-bold text-lg">&times;</button>
            </div>
        @endif

        {{-- Card Profil --}}
        <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md mx-auto" x-data="{
            editing: false,
            name: '{{ $user->name }}',
            email: '{{ $user->email }}',
            originalName: '{{ $user->name }}',
            originalEmail: '{{ $user->email }}'
        }">

            {{-- Foto Profil --}}
            <div class="flex flex-col items-center mb-6">
                <div
                    class="w-28 h-28 rounded-full overflow-hidden mb-3 flex items-center justify-center bg-gradient-to-tr from-blue-400 via-cyan-300 to-blue-200 shadow-inner">
                    <span class="text-4xl">👤</span>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" x-model="name" placeholder="Your name" maxlength="20"
                        pattern="^[a-z][a-z\s]{0,19}$"
                        title="Must start with a lowercase letter and max 20 characters (letters and spaces only)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :readonly="!editing" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" x-model="email" placeholder="your@email.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :readonly="!editing">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                @if ($user->isAdmin())
                    <p class="mb-4 text-sm font-medium text-red-600 text-center">Role: Admin</p>
                @endif

                {{-- Buttons --}}
                <div class="flex flex-col gap-2">
                    <div class="flex gap-3">
                        <template x-if="!editing">
                            <button type="button" @click="editing = true"
                                class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                Edit
                            </button>
                        </template>

                        <template x-if="editing">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                Save
                            </button>
                        </template>

                        <template x-if="editing">
                            <button type="button" @click="editing = false; name = originalName; email = originalEmail;"
                                class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                                Cancel
                            </button>
                        </template>
                    </div>
                </div>
            </form>

            {{-- Logout --}}
            <div class="mt-6">
                <button id="logout-button" type="button"
                    class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('logout-button').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('logout') }}";

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
</x-layouts>
