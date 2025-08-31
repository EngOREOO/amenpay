<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a web request with session authentication
        if ($request->expectsJson()) {
            // API request - check for bearer token
            return $this->handleApiRequest($request, $next);
        } else {
            // Web request - check for session authentication
            return $this->handleWebRequest($request, $next);
        }
    }

    /**
     * Handle API requests with token authentication.
     */
    private function handleApiRequest(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Admin authentication token required'
            ], 401);
        }

        // Validate admin token
        $admin = Admin::where('id', function($query) use ($token) {
            $query->select('tokenable_id')
                  ->from('personal_access_tokens')
                  ->where('token', hash('sha256', $token));
        })->first();

        if (!$admin || !$admin->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive admin token'
            ], 403);
        }

        // Add admin user to request
        $request->merge(['admin_user' => $admin]);
        $request->setUserResolver(fn() => $admin);

        return $next($request);
    }

    /**
     * Handle web requests with session authentication.
     */
    private function handleWebRequest(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated via session
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the admin panel.');
        }

        $admin = Auth::guard('admin')->user();

        // Check if admin is active
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your admin account has been deactivated.');
        }

        // Add admin user to request
        $request->merge(['admin_user' => $admin]);
        $request->setUserResolver(fn() => $admin);

        return $next($request);
    }
}
