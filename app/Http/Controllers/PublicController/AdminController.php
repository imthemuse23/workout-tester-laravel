<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // dashboard
    public function dashboard()
    {
        // hitung user dengan role 'user'
        $userCount = User::where('role', 'user')->count();

        // hitung total workouts
        $workoutCount = Workout::count();

        return view('admin.dashboard', compact('userCount', 'workoutCount'));
    }

    // user
    public function manageUsers()
    {
        // Ambil semua user biasa
        $users = User::where('role', 'user')->get();
        return view('admin.userManagement', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    // workouts
    public function manageWorkouts()
    {
        $workouts = Workout::with('categories')->get(); // load relasi categories juga
        $categories = Category::all();

        return view('admin.workoutsManagement', compact('workouts', 'categories'));
    }

    public function createWorkout()
    {
        $categories = Category::all();
        return view('admin.workoutsCreate', compact('categories'));
    }

    public function storeWorkout(Request $request)
    {
        $request->validate([
            'workout_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'duration'     => 'required|integer|min:1',
            'difficulty'   => 'required|in:Beginner,Intermediate,Advanced',
            'categories'   => 'array',
            'categories.*' => 'exists:categories,id',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['workout_name', 'description', 'duration', 'difficulty']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('workouts', 'public');
        }

        $workout = Workout::create($data);

        // simpan relasi kategori
        if ($request->has('categories')) {
            $workout->categories()->sync($request->categories);
        }

        return redirect()->route('admin.workouts')->with('success', 'Workout berhasil ditambahkan.');
    }

    public function updateWorkout(Request $request, $id)
    {
        $request->validate([
            'workout_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'duration'     => 'required|integer|min:1',
            'difficulty'   => 'required|in:Beginner,Intermediate,Advanced',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        $workout = Workout::findOrFail($id);

        // Hapus gambar lama jika diminta
        if ($request->boolean('remove_image')) {
            if ($workout->image && Storage::disk('public')->exists($workout->image)) {
                Storage::disk('public')->delete($workout->image);
            }
            $workout->image = null;
        }

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // hapus gambar lama
            if ($workout->image && Storage::disk('public')->exists($workout->image)) {
                Storage::disk('public')->delete($workout->image);
            }
            $workout->image = $request->file('image')->store('workouts', 'public');
        }

        // Update field dasar + difficulty
        $workout->workout_name = $request->workout_name;
        $workout->description  = $request->description;
        $workout->duration     = $request->duration;
        $workout->difficulty   = $request->difficulty;
        $workout->save();

        // Sync kategori (jika tidak ada input, kosongkan relasi)
        $workout->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.workouts')->with('success', 'Workout berhasil diperbarui.');
    }



    public function deleteWorkout($id)
    {
        $workout = Workout::findOrFail($id);
        $workout->delete();

        return redirect()->route('admin.workouts')->with('success', 'Workout berhasil dihapus.');
    }

    // category

    public function manageCategory()
    {
        $categories = Category::latest()->get();
        return view('admin.categoryManagement', compact('categories'));
    }

    // CREATE: form tambah kategori
    public function createCategory()
    {
        return view('admin.categoryCreate');
    }

    // STORE: simpan kategori baru
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // UPDATE: update kategori
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    // DELETE: hapus kategori
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
