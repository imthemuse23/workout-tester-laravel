{{-- resources/views/components/header.blade.php --}}
<header class="bg-black">
    <nav class="container mx-auto flex items-center justify-between p-4">
        <!-- Logo -->
        <div class="text-2xl font-extrabold tracking-wide">
            <a href="{{ url('/') }}"
                class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent">
                Workout Tracker
            </a>
        </div>

        <!-- Navigation links (desktop) -->
        <ul class="hidden md:flex gap-8">
            @guest
                <li>
                    <a href="{{ route('home') }}"
                        class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80 transition">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('faq') }}"
                        class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80 transition">
                        FAQ
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}"
                        class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80 transition">
                        Contact
                    </a>
                </li>
            @else
                @if (Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">Dashboard</a>
                    </li>
                    <li><a href="{{ route('admin.users') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">User
                            Management</a></li>
                    <li><a href="{{ route('admin.workouts') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">Workouts
                            Management</a></li>
                    <li><a href="{{ route('admin.categories') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">Workout
                            Category</a></li>
                @else
                    <li><a href="{{ route('home') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">Home</a>
                    </li>
                    <li><a href="{{ route('workouts') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">Workouts</a>
                    </li>
                    <li><a href="{{ route('my-activity') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">My
                            Activity</a></li>
                    <li><a href="{{ route('profile') }}"
                            class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent hover:opacity-80">Profile</a>
                    </li>
                @endif
            @endguest
        </ul>


        <!-- Auth buttons (desktop) -->
        <div class="hidden md:flex gap-3">
            @auth
                @if (Auth::user()->isAdmin())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded">
                            Logout
                        </button>
                    </form>
                @endif
            @endauth
        </div>


        <!-- Mobile menu button -->
        <div class="md:hidden">
            <button id="mobile-menu-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile menu (default hidden) -->
    <div id="mobile-menu" class="hidden md:hidden bg-blue-500">
        <ul class="flex flex-col p-4 gap-3">
            @guest
                <li><a href="{{ route('home') }}" class="hover:text-gray-200">Home</a></li>
                <li><a href="{{ route('workouts') }}" class="hover:text-gray-200">Workouts</a></li>
                <li><a href="{{ route('faq') }}" class="hover:text-gray-200">FAQ</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-gray-200">Contact</a></li>
            @else
                @if (Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-200">Dashboard</a></li>
                @else
                    <li><a href="{{ route('home') }}" class="hover:text-gray-200">Home</a></li>
                    <li><a href="{{ route('workouts') }}" class="hover:text-gray-200">Workouts</a></li>
                    <li><a href="{{ route('my-activity') }}" class="hover:text-gray-200">My Activity</a></li>
                    <li><a href="{{ route('profile') }}" class="hover:text-gray-200">Profile</a></li>
                @endif

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded w-full">
                            Logout
                        </button>
                    </form>
                </li>
            @endguest
        </ul>
    </div>

    {{-- Script untuk toggle mobile menu --}}
    <script>
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</header>
