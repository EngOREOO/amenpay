<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication token required'
            ], 401);
        }

        $session = UserSession::findByToken($token);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token'
            ], 401);
        }

        $user = $session->user;

        if (!$user || !$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'User account is not active'
            ], 403);
        }

        // Add user to request
        $request->merge(['user' => $user]);
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
