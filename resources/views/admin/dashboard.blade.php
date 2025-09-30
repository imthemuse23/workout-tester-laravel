<x-layouts title="Admin Dashboard">
    <!-- Judul -->
    <h1
        class="text-3xl md:text-4xl font-extrabold
               bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-200
               bg-clip-text text-transparent mb-10">
        Admin Dashboard
    </h1>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Total Users -->
        <div
            class="p-6 bg-black rounded-2xl shadow-lg border border-gray-700
                    hover:shadow-2xl transition transform hover:-translate-y-1 text-center">
            <h5 class="text-gray-300 mb-2">Total Users</h5>
            <p
                class="text-4xl font-extrabold
                      bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                      bg-clip-text text-transparent">
                {{ $userCount }}
            </p>
        </div>

        <!-- Total Workouts -->
        <div
            class="p-6 bg-black rounded-2xl shadow-lg border border-gray-700
                    hover:shadow-2xl transition transform hover:-translate-y-1 text-center">
            <h5 class="text-gray-300 mb-2">Total Workouts</h5>
            <p
                class="text-4xl font-extrabold
                      bg-gradient-to-r from-green-400 via-lime-300 to-green-200
                      bg-clip-text text-transparent">
                {{ $workoutCount }}
            </p>
        </div>
    </div>
</x-layouts>
