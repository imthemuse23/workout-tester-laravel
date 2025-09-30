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
                <div>
                    <label class="block font-medium mb-1 text-gray-700">Password</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-lg p-3 bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-cyan-300 focus:border-cyan-300">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

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
