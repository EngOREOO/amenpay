<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Debug: Log the credentials being used
        \Log::info('Admin login attempt', [
            'email' => $credentials['email'],
            'remember' => $remember
        ]);

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $admin = Auth::guard('admin')->user();
            
            // Debug: Log successful authentication
            \Log::info('Admin login successful', [
                'admin_id' => $admin->id,
                'admin_name' => $admin->name
            ]);
            
            // Update last login
            $admin->updateLastLogin();
            
            // Generate API token for API requests
            $token = $admin->createToken('admin-token')->plainTextToken;
            
            $request->session()->regenerate();
            
            // Use direct redirect instead of intended to avoid conflicts
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, ' . $admin->name . '!');
        }

        // Debug: Log failed authentication
        \Log::info('Admin login failed', [
            'email' => $credentials['email']
        ]);

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        // Revoke API token
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->user()->tokens()->delete();
        }
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been successfully logged out.');
    }

    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('admin.dashboard', compact('admin'));
    }

    /**
     * Get admin profile.
     */
    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('admin.profile', compact('admin'));
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify current password if changing password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('new_password')) {
            $admin->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}
