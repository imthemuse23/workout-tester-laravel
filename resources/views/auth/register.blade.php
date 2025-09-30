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
                    value="{{ old('name') }}">
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
            <div>
                <label class="block font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
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
