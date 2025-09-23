{{-- header.blade.php --}}
<header class="bg-blue-600 text-white shadow-md">
    <nav class="container mx-auto flex items-center justify-between p-4">
        <!-- Logo -->
        <div class="text-xl font-bold">
            <a href="{{ url('/') }}">Workout Tracker</a>
        </div>

        <!-- Navigation links -->
        <ul class="hidden md:flex gap-6">
            <li><a href="#home" class="hover:text-gray-200">Home</a></li>
            <li><a href="#workouts" class="hover:text-gray-200">Workouts</a></li>
            <li><a href="#faq" class="hover:text-gray-200">FAQ</a></li>
            <li><a href="#testimonials" class="hover:text-gray-200">Testimonials</a></li>
            <li><a href="#gallery" class="hover:text-gray-200">Gallery</a></li>
            <li><a href="#contact" class="hover:text-gray-200">Contact</a></li>
        </ul>

        <!-- Auth buttons -->
        <div class="flex gap-3">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="bg-white text-blue-600 hover:bg-gray-100 px-4 py-1 rounded">Login</a>
                <a href="{{ route('register') }}"
                    class="bg-white text-blue-600 hover:bg-gray-100 px-4 py-1 rounded">Sign Up</a>
            @endauth
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden">
            <button id="mobile-menu-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-blue-500">
        <ul class="flex flex-col p-4 gap-3">
            <li><a href="#home" class="hover:text-gray-200">Home</a></li>
            <li><a href="#workouts" class="hover:text-gray-200">Workouts</a></li>
            <li><a href="#faq" class="hover:text-gray-200">FAQ</a></li>
            <li><a href="#testimonials" class="hover:text-gray-200">Testimonials</a></li>
            <li><a href="#gallery" class="hover:text-gray-200">Gallery</a></li>
            <li><a href="#contact" class="hover:text-gray-200">Contact</a></li>
            @guest
                <li><a href="{{ route('login') }}" class="bg-white text-blue-600 px-4 py-1 rounded">Login</a></li>
                <li><a href="{{ route('register') }}" class="bg-white text-blue-600 px-4 py-1 rounded">Sign Up</a></li>
            @endguest
        </ul>
    </div>

    {{-- Script sederhana untuk toggle mobile menu --}}
    <script>
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</header>
