<x-layouts title="Register">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2
            class="text-3xl font-extrabold mb-6 text-center
                    bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200
                    bg-clip-text text-transparent">
            Register</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Name -->
            <div>
                <label class="block font-medium mb-1">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400"
                    value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Email -->
            <div>
                <label class="block font-medium mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400"
                    value="{{ old('email') }}">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative">
                <label class="block font-medium mb-1">Password</label>
                <input type="password" name="password" id="registerPassword"
                    class="w-full border rounded p-2 pr-10 focus:ring-2 focus:ring-blue-400">

                <!-- Tombol toggle -->
                <button type="button" id="toggleRegisterPassword"
                    class="absolute right-0 top-10 px-3 flex items-center text-gray-500 hover:text-gray-700">
                    <!-- Ikon eye -->
                    <svg id="registerEyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>

                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <script>
                const registerPasswordInput = document.getElementById('registerPassword');
                const toggleRegisterBtn = document.getElementById('toggleRegisterPassword');
                const registerEyeIcon = document.getElementById('registerEyeIcon');

                toggleRegisterBtn.addEventListener('click', function() {
                    if (registerPasswordInput.type === 'password') {
                        registerPasswordInput.type = 'text';
                        registerEyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                a10.05 10.05 0 012.392-3.855m3.6-2.67A9.97 9.97 0 0112 5c4.477 0
                8.268 2.943 9.542 7a10.05 10.05 0 01-1.513 2.647M3 3l18 18"/>`;
                    } else {
                        registerPasswordInput.type = 'password';
                        registerEyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0
                8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7
                -4.477 0-8.268-2.943-9.542-7z" />`;
                    }
                });
            </script>


            <!-- Confirm Password -->
            <div>
                <label class="block font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
                @error('password_confirmation')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full px-6 py-3 rounded-xl font-semibold
                    bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600 text-white shadow-md
                        hover:scale-105 hover:-translate-y-1 hover:shadow-lg transition duration-300 ease-in-out">
                Register
            </button>



            <!-- Link to login -->
            <p class="text-sm text-center mt-4">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300 bg-clip-text text-transparent font-medium hover:underline">>Login</a>
            </p>
    </div>
</x-layouts>
