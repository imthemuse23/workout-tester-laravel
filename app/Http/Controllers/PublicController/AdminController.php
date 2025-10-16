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

        return redirect()->route('admin.users')->with('success', 'User has been successfully deleted.');
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
            'workout_name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/',
            'description'  => 'nullable|string|max:200|regex:/^[a-zA-Z\s]+$/',
            'duration'     => 'required|integer|min:1',
            'difficulty'   => 'required|in:Beginner,Intermediate,Advanced',
            'categories'   => 'array',
            'categories.*' => 'exists:categories,id',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'workout_name.regex' => 'Nama Workout hanya boleh huruf dan spasi.',
            'description.regex'  => 'Deskripsi hanya boleh huruf dan spasi.',
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

        return redirect()->route('admin.workouts')->with('success', 'Workout successfully added.');
    }

    public function updateWorkout(Request $request, $id)
    {
        try {
            $request->validate([
                'workout_name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[^\d]+$/', // tidak boleh mengandung angka
                ],
                'description'  => [
                    'nullable',
                    'string',
                    'regex:/^[^\d]+$/', // tidak boleh mengandung angka
                ],
                'duration'     => 'required|integer|min:1',
                'difficulty'   => 'required|in:Beginner,Intermediate,Advanced',
                'categories'   => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'remove_image' => 'nullable|boolean',
            ], [
                'workout_name.required' => 'Workout name is required.',
                'workout_name.regex'    => 'Workout name cannot contain numbers.',
                'description.regex'     => 'Description cannot contain numbers.',
                'duration.required'     => 'Duration is required.',
                'duration.integer'      => 'Duration must be a number.',
                'difficulty.required'   => 'Please select a difficulty level.',
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

            return redirect()->route('admin.workouts')->with('success', 'Workout updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal → balik ke halaman sebelumnya + modal tetap terbuka
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_edit_modal', true);
        }
    }

    public function deleteWorkout($id)
    {
        $workout = Workout::findOrFail($id);
        $workout->delete();

        return redirect()->route('admin.workouts')->with('success', 'Workout successfully deleted.');
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
            'workout_name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/',
            'description'  => 'nullable|string|max:200|regex:/^[a-zA-Z\s]+$/',
        ], [
            'workout_name.regex' => 'Category name hanya boleh huruf dan spasi.',
            'description.regex'  => 'Description hanya boleh huruf dan spasi.',
        ]);

        Category::create([
            'name'        => $request->workout_name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category added successfully.');
    }

    // UPDATE
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'workout_name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/|unique:categories,name,' . $category->id,
            'description'  => 'nullable|string|max:200|regex:/^[a-zA-Z\s]+$/',
        ], [
            'workout_name.regex' => 'Category name hanya boleh huruf dan spasi.',
            'description.regex'  => 'Description hanya boleh huruf dan spasi.',
        ]);

        $category->update([
            'name'        => $request->workout_name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }

    // DELETE
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category successfully deleted.');
    }
}
