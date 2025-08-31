<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpCode;
use App\Models\UserSession;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Test endpoint to verify API is working.
     */
    public function test(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'P-Finance API is working!',
            'data' => [
                'version' => '1.0.0',
                'timestamp' => now()->toISOString(),
                'environment' => config('app.env')
            ]
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|unique:users,phone|regex:/^\+966[0-9]{9}$/',
            'name' => 'required|string|max:255',
            'national_id' => 'nullable|string|unique:users,national_id|regex:/^[0-9]{10}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create OTP for registration
        $otp = OtpCode::createForPhone($request->phone, 'registration');

        // In a real application, send SMS here
        // For development, we'll return the OTP in response
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'phone' => $request->phone,
                'otp' => $otp->code, // Remove this in production
                'expires_in' => 300 // 5 minutes
            ]
        ]);
    }

    /**
     * Verify OTP and complete registration.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/',
            'code' => 'required|string|size:6',
            'type' => 'required|in:registration,login,reset',
            'name' => 'required_if:type,registration|string|max:255',
            'national_id' => 'nullable|string|regex:/^[0-9]{10}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify OTP
        if (!OtpCode::verify($request->phone, $request->code, $request->type)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        if ($request->type === 'registration') {
            // Create user
            $user = User::create([
                'phone' => $request->phone,
                'name' => $request->name,
                'national_id' => $request->national_id,
                'is_verified' => true,
                'phone_verified_at' => now(),
                'status' => 'active',
            ]);

            // Create wallet for user
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'wallet_number' => Wallet::generateWalletNumber(),
                'balance' => 0.00,
                'currency' => 'SAR',
                'status' => 'active',
            ]);

            // Create session
            $session = UserSession::createForUser($user, [
                'device' => $request->header('User-Agent'),
                'platform' => 'mobile'
            ], $request->ip());

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully',
                'data' => [
                    'user' => $user,
                    'wallet' => $wallet,
                    'token' => $session->token,
                    'expires_at' => $session->expires_at
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully'
        ]);
    }

    /**
     * Login with phone number.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Account is not active'
            ], 403);
        }

        // Create OTP for login
        $otp = OtpCode::createForPhone($request->phone, 'login');

        // In a real application, send SMS here
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'phone' => $request->phone,
                'otp' => $otp->code, // Remove this in production
                'expires_in' => 300 // 5 minutes
            ]
        ]);
    }

    /**
     * Complete login with OTP.
     */
    public function loginWithOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify OTP
        if (!OtpCode::verify($request->phone, $request->code, 'login')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Create session
        $session = UserSession::createForUser($user, [
            'device' => $request->header('User-Agent'),
            'platform' => 'mobile'
        ], $request->ip());

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user->load('wallet'),
                'token' => $session->token,
                'expires_at' => $session->expires_at
            ]
        ]);
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/',
            'type' => 'required|in:registration,login,reset',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create new OTP
        $otp = OtpCode::createForPhone($request->phone, $request->type);

        // In a real application, send SMS here
        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully',
            'data' => [
                'phone' => $request->phone,
                'otp' => $otp->code, // Remove this in production
                'expires_in' => 300 // 5 minutes
            ]
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        
        if ($token) {
            UserSession::where('token', $token)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Refresh token.
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        $session = UserSession::findByToken($token);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        // Extend session
        $session->extend(60 * 24 * 30); // 30 days

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $session->token,
                'expires_at' => $session->expires_at
            ]
        ]);
    }

    /**
     * Forgot password - send OTP to reset password.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user exists and is active
        $user = User::where('phone', $request->phone)->first();
        if (!$user || $user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'User not found or account is not active'
            ], 404);
        }

        // Create OTP for password reset
        $otp = OtpCode::createForPhone($request->phone, 'reset');

        // In a real application, send SMS here
        // For development, we'll return the OTP in response
        return response()->json([
            'success' => true,
            'message' => 'Password reset OTP sent successfully',
            'data' => [
                'phone' => $request->phone,
                'otp' => $otp->code, // Remove this in production
                'expires_in' => 300 // 5 minutes
            ]
        ]);
    }

    /**
     * Reset password with OTP verification.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/|exists:users,phone',
            'code' => 'required|string|size:6',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify OTP
        if (!OtpCode::verify($request->phone, $request->code, 'reset')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        // Update user password
        $user = User::where('phone', $request->phone)->first();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Invalidate all existing sessions
        $user->sessions()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. Please login with your new password.'
        ]);
    }

    /**
     * Change password for authenticated user.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Check if user has a password set (for users who registered with OTP only)
        if (!$user->password) {
            return response()->json([
                'success' => false,
                'message' => 'Please set a password first using forgot password'
            ], 400);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Invalidate all existing sessions except current one
        $currentToken = $request->bearerToken();
        $user->sessions()->where('token', '!=', $currentToken)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}
