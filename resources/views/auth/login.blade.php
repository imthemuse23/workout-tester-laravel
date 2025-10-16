<x-layouts title="Login">
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="max-w-md w-full p-8 bg-white rounded-2xl shadow-lg border border-gray-200">

            <!-- Judul -->
            <h2
                class="text-3xl font-extrabold mb-6 text-center
                    bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200
                    bg-clip-text text-transparent">
                Login
            </h2>

            <!-- Success & Error Alert -->
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Email -->
                <div>
                    <label class="block font-medium mb-1 text-gray-700">Email</label>
                    <input type="email" name="email"
                        class="w-full border border-gray-300 rounded-lg p-3 bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-cyan-300 focus:border-cyan-300"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative">
                    <label class="block font-medium mb-1 text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-lg p-3 bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-cyan-300 focus:border-cyan-300 pr-10">

                    <!-- Eye icon -->
                    <button type="button" id="togglePassword"
                        class="absolute right-0 top-11 px-3 flex items-center text-gray-500 hover:text-gray-700">

                        <!-- SVG eye -->
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>

                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <script>
                    const passwordInput = document.getElementById('password');
                    const toggleBtn = document.getElementById('togglePassword');
                    const eyeIcon = document.getElementById('eyeIcon');

                    toggleBtn.addEventListener('click', function() {
                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                            // Ganti icon menjadi eye-off
                            eyeIcon.innerHTML =
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.392-3.855m3.6-2.67A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.513 2.647M3 3l18 18"/>';
                        } else {
                            passwordInput.type = "password";
                            // Kembalikan icon eye
                            eyeIcon.innerHTML =
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                        }
                    });
                </script>


                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600 text-white font-semibold p-3 rounded-lg hover:opacity-90 transition">
                    Login
                </button>

            </form>

            <p class="text-sm text-center mt-6 text-black">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 bg-clip-text text-transparent font-medium hover:underline">
                    Register
                </a>
            </p>

        </div>
    </div>
</x-layouts>
