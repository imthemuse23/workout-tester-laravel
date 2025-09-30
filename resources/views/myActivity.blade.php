<x-layouts title="My Activity">
    <div class="container mx-auto px-4 py-8 space-y-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-600">My Activity</h1>

        {{-- Ringkasan Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="p-6 bg-black rounded-xl shadow-lg text-center">
                <h2
                    class="font-bold text-xl mb-2
                           bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                           bg-clip-text text-transparent">
                    Total Workouts
                </h2>
                <p
                    class="text-4xl font-extrabold
                          bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                          bg-clip-text text-transparent">
                    {{ $totalWorkouts ?? 0 }}
                </p>
            </div>

            <div class="p-6 bg-black rounded-xl shadow-lg text-center">
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
                    {{ $totalDuration ?? 0 }} menit
                </p>
            </div>

            <div class="p-6 bg-black rounded-xl shadow-lg text-center">
                <h2
                    class="font-bold text-xl mb-2
                           bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                           bg-clip-text text-transparent">
                    Completed
                </h2>
                <p
                    class="text-4xl font-extrabold
                          bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-300
                          bg-clip-text text-transparent">
                    {{ $completedWorkouts ?? 0 }}
                </p>
            </div>
        </div>

        {{-- Daftar Workout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myWorkouts as $workout)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 relative" x-data="{
                    remaining: {{ $workout->pivot->remaining_time ?? $workout->duration * 60 }},
                    timer: null,
                    completed: {{ $workout->pivot->completed ? 1 : 0 }},
                    started: {{ $workout->pivot->remaining_time ? 1 : 0 }},
                    startTimer() {
                        if (!this.completed && !this.timer) {
                            this.started = 1;
                            this.timer = setInterval(() => {
                                if (this.remaining > 0) {
                                    this.remaining--;
                                } else {
                                    clearInterval(this.timer);
                                    this.timer = null;
                                    this.completed = 1;
                                    this.updateTimer();
                                }
                            }, 1000);
                        }
                    },
                    pauseTimer() {
                        clearInterval(this.timer);
                        this.timer = null;
                        this.updateTimer();
                    },
                    updateTimer() {
                        fetch('{{ route('my.activity.updateTimer') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                workout_id: {{ $workout->id }},
                                remaining_time: this.remaining,
                                completed: this.completed
                            })
                        });
                    }
                }">

                    <div class="absolute top-2 right-2">
                        <form method="POST" action="{{ route('my.activity.deleteWorkout', $workout->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                X
                            </button>
                        </form>
                    </div>

                    {{-- Gambar Workout --}}
                    @if ($workout->image)
                        <img src="{{ asset('storage/' . $workout->image) }}" alt="{{ $workout->workout_name }}"
                            class="w-full h-40 object-cover rounded mb-4">
                    @else
                        <div
                            class="w-full h-40 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-500 italic">
                            No Image
                        </div>
                    @endif

                    {{-- Nama & Deskripsi --}}
                    <h3 class="font-bold text-xl mb-2">{{ $workout->workout_name }}</h3>
                    <p class="text-gray-600 mb-2">{{ $workout->description }}</p>
                    <p class="text-sm text-gray-500 mb-1">Durasi: {{ $workout->duration }} menit</p>
                    <p class="text-sm mb-2">
                        <span
                            class="px-2 py-1 rounded text-xs font-medium
                            @if ($workout->difficulty === 'Beginner') bg-green-100 text-green-700
                            @elseif($workout->difficulty === 'Intermediate') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $workout->difficulty }}
                        </span>
                    </p>

                    {{-- Status & Timer --}}
                    <p class="text-sm mb-2">Status: <span x-text="completed ? 'Selesai' : 'Sedang Berlangsung'"></span>
                    </p>
                    <p class="text-sm mb-4" x-show="!completed">Sisa waktu: <span x-text="remaining"></span> detik</p>

                    {{-- Tombol Start / Pause / Resume --}}
                    <div class="flex gap-2">
                        <button x-show="!completed && !started" @click="startTimer()"
                            class="flex-1 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Start
                        </button>

                        <button x-show="!completed && timer" @click="pauseTimer()"
                            class="flex-1 px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">
                            Pause
                        </button>

                        <button x-show="!completed && !timer && started" @click="startTimer()"
                            class="flex-1 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                            Resume
                        </button>

                        <button x-show="completed" disabled
                            class="flex-1 px-4 py-2 bg-gray-300 text-white rounded cursor-not-allowed">
                            Selesai
                        </button>
                    </div>

                </div>

            @empty
                <p class="col-span-3 text-center text-gray-500">There are no workout activities yet.</p>
            @endforelse
        </div>
    </div>
</x-layouts>
