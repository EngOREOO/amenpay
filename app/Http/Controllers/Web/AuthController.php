<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Update last login
            Auth::user()->update(['last_login_at' => now()]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'phone' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('phone'));
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|max:20',
            'email' => 'nullable|email|unique:users,email|max:255',
            'national_id' => 'nullable|string|unique:users,national_id|max:20',
            'password' => 'required|string|min:8|confirmed',
            'language' => 'required|in:ar,en',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'national_id' => $request->national_id,
            'password' => Hash::make($request->password),
            'language' => $request->language,
            'is_verified' => false,
            'status' => 'active',
        ]);

        // Create wallet for the user
        $user->wallet()->create([
            'wallet_number' => 'WAL' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
            'balance' => 0.00,
            'currency' => 'SAR',
            'status' => 'active',
        ]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully! Welcome to P-Finance.');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
