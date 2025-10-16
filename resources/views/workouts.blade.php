<x-layouts title="Workouts">
    <h1
        class="text-3xl font-bold mb-6
            bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
            bg-clip-text text-transparent">
        Workout List
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse ($workouts as $workout)
            <div
                class="bg-black rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 duration-300 p-4">

                {{-- Gambar --}}
                @if ($workout->image)
                    <img src="{{ asset('storage/' . $workout->image) }}" alt="{{ $workout->workout_name }}"
                        class="w-full h-44 object-cover rounded-xl mb-3 hover:scale-105 transition transform duration-300">
                @else
                    <div
                        class="w-full h-44 bg-gray-200 flex items-center justify-center rounded-xl mb-3 text-gray-500 italic">
                        No Image
                    </div>
                @endif

                {{-- Nama workout --}}
                <h2
                    class="text-lg font-semibold mb-1
                        bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                        bg-clip-text text-transparent">
                    {{ $workout->workout_name }}
                </h2>
                <p class="text-gray-600 mb-2 line-clamp-3">{{ $workout->description }}</p>
                <p class="text-sm text-gray-500 mb-2">Durasi: {{ $workout->duration }} menit</p>
                <p class="text-sm mb-3">
                    <span
                        class="px-2 py-1 rounded text-xs font-medium
                        @if ($workout->difficulty === 'Beginner') bg-green-100/80 text-green-800
                        @elseif ($workout->difficulty === 'Intermediate') bg-yellow-100/80 text-yellow-800
                        @else bg-red-100/80 text-red-800 @endif
                        backdrop-blur-sm">
                        {{ $workout->difficulty }}
                    </span>
                </p>

                {{-- Tombol tambah workout --}}
                @guest
                    <a href="{{ route('login') }}"
                        class="mt-2 inline-block w-full text-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-400 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-cyan-500 transition">
                        Tambah Workout
                    </a>
                @else
                    <form action="{{ route('workouts.add', $workout->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="mt-2 w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-400 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-cyan-500 transition">
                            Add Workout
                        </button>
                    </form>
                @endguest
            </div>
        @empty
            <p class="text-gray-500 italic col-span-3 text-center">There are no workouts available yet.</p>
        @endforelse
    </div>
</x-layouts>
