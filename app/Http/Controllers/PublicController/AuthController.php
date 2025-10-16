<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle register process
    public function register(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:20',
                'regex:/^[a-z\s]+$/', // Hanya huruf kecil dan spasi
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@gmail.com')) {
                        $fail('Email must contain @gmail.com.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{6,}$/',
                'confirmed',
            ],
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name must contain a maximum of 20 characters.',
            'name.regex' => 'Name must contain only lowercase letters and spaces.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one symbol.',
            'password.confirmed' => 'Password confirmation does not match.'
        ]);



        // Assign role automatically
        $role = $request->email === 'adminworkout@gmail.com' ? 'admin' : 'user';

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login process
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            /** @var \App\Models\User $user  */
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Successfully logged in as Admin!');
            }

            // If normal user → redirect to user dashboard
            return redirect()->route('home')
                ->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
