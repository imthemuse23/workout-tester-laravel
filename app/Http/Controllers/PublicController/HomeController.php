<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function indexHome()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $latestWorkouts = Workout::orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('home', compact('latestWorkouts'));
    }

    public function indexWorkout()
    {
        $workouts = Workout::all(); // ambil semua data workout
        return view('workouts', compact('workouts'));
    }

    public function indexMyActivity()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Ambil workout yang diambil user beserta pivot info
        $myWorkouts = $user->workouts()->with('categories')->get();

        // Bisa juga hitung statistik ringkas
        $totalWorkouts = $myWorkouts->count();
        $totalDuration = $myWorkouts->sum('duration'); // total duration dari workout table
        $completedWorkouts = $myWorkouts->where('pivot.completed', true)->count();

        return view('myactivity', compact('myWorkouts', 'totalWorkouts', 'totalDuration', 'completedWorkouts'));
    }

    public function addWorkout(Request $request, Workout $workout)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah workout sudah diambil user
        if (!$user->workouts->contains($workout->id)) {
            $user->workouts()->attach($workout->id, [
                'remaining_time' => $workout->duration * 2, // simpan detik
                'completed' => false,
            ]);
        }

        return redirect()->route('my-activity')->with('success', 'Workout successfully added!');
    }

    public function updateTimer(Request $request)
    {
        $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'remaining_time' => 'required|integer|min:0',
            'completed' => 'required|boolean',
        ]);

        $user = Auth::user();

        // Update pivot table

        $user->workouts()->updateExistingPivot($request->workout_id, [
            'remaining_time' => $request->remaining_time,
            'completed' => $request->completed,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteWorkout(Request $request, Workout $workout)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $user->workouts()->detach($workout->id); // hapus pivot record

        return redirect()->route('my-activity')->with('success', 'Workout successfully deleted!');
    }

    // profile
    public function indexProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile')->with('success', 'Profile successfully updated!');
    }
}
