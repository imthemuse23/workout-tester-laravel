<?php

// routes/web.php

use App\Http\Controllers\PublicController\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController\AuthController;
use App\Http\Controllers\PublicController\HomeController;
use Illuminate\Support\Facades\Auth;

// -----------------
// Halaman Home bisa diakses semua
// -----------------
Route::get('/', [HomeController::class, 'indexHome'])->name('home');

// -----------------
// Guest routes
// -----------------
Route::middleware('guest')->group(function () {
    Route::get('/workouts', [HomeController::class, 'indexWorkout'])->name('workouts');
    Route::view('/faq', 'faq')->name('faq');
    Route::view('/testimonials', 'testimonials')->name('testimonials');
    Route::view('/contact', 'contact')->name('contact');

    // Auth
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// -----------------
// Authenticated User routes
// -----------------
Route::middleware('auth')->group(function () {
    Route::get('/workouts', [HomeController::class, 'indexWorkout'])->name('workouts');
    Route::post('/workouts/{workout}/add', [HomeController::class, 'addWorkout'])->name('workouts.add');

    Route::get('/my-activity', [HomeController::class, 'indexMyActivity'])->name('my-activity');
    Route::post('/my-activity/update-timer', [HomeController::class, 'updateTimer'])->name('my.activity.updateTimer');
    Route::delete('/my-activity/workout/{workout}', [HomeController::class, 'deleteWorkout'])->name('my.activity.deleteWorkout');

    Route::get('/profile', [HomeController::class, 'indexProfile'])->name('profile');
    Route::get('/profile', [HomeController::class, 'indexProfile'])->name('profile');
    Route::post('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// -----------------
// Admin routes
// -----------------
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // users
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // workouts
    Route::get('/workouts', [AdminController::class, 'manageWorkouts'])->name('admin.workouts');
    Route::get('/workouts/create', [AdminController::class, 'createWorkout'])->name('admin.workouts.create');
    Route::post('/workouts', [AdminController::class, 'storeWorkout'])->name('admin.workouts.store');
    Route::put('/workouts/{id}', [AdminController::class, 'updateWorkout'])->name('admin.workouts.update');
    Route::delete('/workouts/{id}', [AdminController::class, 'deleteWorkout'])->name('admin.workouts.delete');

    // categories
    Route::get('/categories', [AdminController::class, 'manageCategory'])->name('admin.categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
});
