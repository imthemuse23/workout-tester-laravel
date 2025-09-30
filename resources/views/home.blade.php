<x-layouts title="Home">
    @auth
        <!-- Greeting -->
        <section class="bg-black py-12">
            <div class="container mx-auto px-4 text-center md:text-left">
                <h1
                    class="text-3xl md:text-4xl font-extrabold mb-2
                    bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                    bg-clip-text text-transparent animate-fade-in">
                    Welcome, {{ Auth::user()->name }}!
                </h1>

                <p class="text-white text-lg md:text-xl animate-fade-in delay-200">
                    Record your workout and stay on top of your fitness goals.
                </p>
            </div>
        </section>

        <!-- Ringkasan Workout / Stats -->
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="p-6 bg-black rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 text-center">
                    <h2
                        class="font-bold text-xl mb-2
                       bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                       bg-clip-text text-transparent">
                        Workouts Total
                    </h2>
                    <p
                        class="text-4xl font-extrabold
                      bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                      bg-clip-text text-transparent">
                        {{ $totalWorkouts ?? 0 }}
                    </p>
                </div>

                <div
                    class="p-6 bg-black rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 text-center">
                    <h2
                        class="font-bold text-xl mb-2
                       bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                       bg-clip-text text-transparent">
                        Total Duration
                    </h2>
                    <p
                        class="text-4xl font-extrabold
                      bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                      bg-clip-text text-transparent">
                        {{ $totalDuration ?? '0m' }}
                    </p>
                </div>

                <div
                    class="p-6 bg-black rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 text-center">
                    <h2
                        class="font-bold text-xl mb-2
                        bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                        bg-clip-text text-transparent">
                        Workout Completed
                    </h2>
                    <p
                        class="text-4xl font-extrabold
                        bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                        bg-clip-text text-transparent">
                        {{ $completedWorkouts ?? 0 }}
                    </p>
                </div>
            </div>
        </section>



      <!-- Daftar Workout -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-6
                bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                bg-clip-text text-transparent">
            Latest Workout
        </h2>

        @php
            $count = $latestWorkouts->count();
            $colsClass = $count >= 3 ? 'md:grid-cols-3' : ($count == 2 ? 'md:grid-cols-2' : 'md:grid-cols-1');
        @endphp

        <div class="grid grid-cols-1 {{ $colsClass }} gap-6">
            @forelse ($latestWorkouts as $workout)
                <div class="bg-black rounded-3xl shadow-md hover:shadow-xl transition transform hover:-translate-y-2 duration-300">

                    {{-- Gambar --}}
                    <div class="overflow-hidden rounded-t-3xl relative">
                        @if ($workout->image)
                            <img src="{{ asset('storage/' . $workout->image) }}" alt="{{ $workout->workout_name }}"
                                class="w-full h-52 object-cover hover:scale-105 transition transform duration-300">
                        @else
                            <div class="w-full h-52 bg-gray-200 flex items-center justify-center text-gray-400 italic">
                                No Image
                            </div>
                        @endif

                        {{-- Badge Difficulty --}}
                        <span
                            class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold
                            @if ($workout->difficulty === 'Beginner') bg-green-100/80 text-green-800
                            @elseif ($workout->difficulty === 'Intermediate') bg-yellow-100/80 text-yellow-800
                            @else bg-red-100/80 text-red-800 @endif
                            backdrop-blur-sm">
                            {{ $workout->difficulty }}
                        </span>
                    </div>

                    {{-- Konten --}}
                    <div class="p-6 flex flex-col gap-2">
                        <h3 class="text-xl font-bold mb-2
                                   bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                                   bg-clip-text text-transparent">
                            {{ $workout->workout_name }}
                        </h3>

                        <p class="text-gray-600 mb-2 line-clamp-3">{{ $workout->description }}</p>
                        <p class="text-sm text-gray-500 mb-4">Durasi: {{ $workout->duration }} menit</p>

                        {{-- Tombol tambah workout --}}
                        @guest
                            <a href="{{ route('login') }}"
                                class="block w-full text-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-cyan-500 transition">
                                Tambah Workout
                            </a>
                        @else
                            <button
                                class="block w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-cyan-500 transition">
                                Add Workout
                            </button>
                        @endguest
                    </div>
                </div>
            @empty
                <p class="col-span-3 text-center text-gray-500">No Workout yet.</p>
            @endforelse
        </div>
    </div>
</section>


        {{-- Animasi (bisa ditaruh di app.css atau tailwind config) --}}
        <style>
            .animate-fade-in {
                animation: fadeIn 1s ease forwards;
            }

            .animate-fade-in.delay-200 {
                animation-delay: 0.2s;
            }

            @keyframes fadeIn {
                0% {
                    opacity: 0;
                    transform: translateY(10px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @else
        <!-- Hero Section untuk guest -->
        <section class="bg-black py-16 text-white rounded-3xl mx-4 shadow-2xl backdrop-blur-md">
            <div class="container mx-auto flex flex-col md:flex-row items-center gap-10 px-6">

                <!-- Gambar workout illustration -->
                <div class="md:w-1/2 flex justify-center">
                    <img src="{{ asset('assets/image/gym.jpg') }}" alt="Workout Illustration"
                        class="rounded-2xl shadow-2xl hover:scale-105 hover:shadow-blue-500/30
                        transform transition duration-500 ease-in-out">
                </div>

                <!-- Konten teks -->
                <div class="md:w-1/2 space-y-8 text-center md:text-left">
                    <h1
                        class="text-3xl md:text-5xl font-extrabold
                        bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200
                        bg-clip-text text-transparent drop-shadow-md">
                        Start your fitness journey today!
                    </h1>

                    <p class="text-gray-300 text-lg leading-relaxed max-w-lg">
                        Workout Tracker helps you log your workouts, track progress, and stay motivated.
                        Join now and achieve your fitness goals with ease.
                    </p>

                    <!-- Tombol -->
                    <div class="flex gap-4 mt-6 justify-center md:justify-start">
                        <a href="{{ route('register') }}"
                            class="px-6 py-3 rounded-xl font-semibold
                            bg-gradient-to-r from-blue-600 to-cyan-500
                            transform hover:scale-105 hover:-translate-y-1
                            transition duration-300 ease-in-out">
                            Sign Up
                        </a>
                        <a href="{{ route('login') }}"
                            class="px-6 py-3 rounded-xl font-semibold
                            bg-white/10 backdrop-blur-md border border-white/20
                            text-blue-300 hover:text-white
                            hover:bg-white/20 hover:shadow-lg
                            transform hover:scale-105 hover:-translate-y-1
                            transition duration-300 ease-in-out">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </section>



        <!-- Section Fitur -->
        <section class="mt-20 px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                <!-- Fitur 1 -->
                <div
                    class="p-8 bg-black rounded-2xl shadow hover:shadow-xl transition text-center border border-gray-200 max-w-sm mx-auto">
                    <div class="text-5xl mb-4">📓</div>
                    <h3
                        class="font-semibold text-xl mb-2 bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent">
                        Log Workouts
                    </h3>
                    <p class="text-gray-300">Easily record every exercise, from sets and reps to duration and intensity.</p>
                </div>

                <!-- Fitur 2 -->
                <div
                    class="p-8 bg-black rounded-2xl shadow hover:shadow-xl transition text-center border border-gray-200 max-w-sm mx-auto">
                    <div class="text-5xl mb-4">📈</div>
                    <h3
                        class="font-semibold text-xl mb-2 bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent">
                        Track Progress
                    </h3>
                    <p class="text-gray-300">Visualize your improvements with weekly and monthly performance stats.</p>
                </div>

                <!-- Fitur 3 -->
                <div
                    class="p-8 bg-black rounded-2xl shadow hover:shadow-xl transition text-center border border-gray-200 max-w-sm mx-auto">
                    <div class="text-5xl mb-4">🔥</div>
                    <h3
                        class="font-semibold text-xl mb-2 bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200 bg-clip-text text-transparent">
                        Stay Motivated
                    </h3>
                    <p class="text-gray-300">Get reminders, fitness tips, and challenges to keep your momentum strong.</p>
                </div>

            </div>
        </section>




        <!-- Section Gambar -->
        <section class="py-20">
            <img src="assets/image/ilustrasi2.jpg" alt="Workout Illustration" class="w-full h-auto shadow-lg">
            <div class="text-center mt-6">
            </div>
        </section>



        </div>
        </div>
        </section>

    @endauth
</x-layouts>
