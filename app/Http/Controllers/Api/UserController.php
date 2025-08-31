<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Get user profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load(['wallet', 'cards']);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'national_id' => 'sometimes|string|unique:users,national_id,' . $user->id . '|regex:/^[0-9]{10}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'national_id']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()
        ]);
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $file = $request->file('avatar');
        $filename = 'avatars/' . time() . '_' . $file->getClientOriginalName();
        
        // Resize and optimize image
        $image = Image::make($file);
        $image->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        Storage::disk('public')->put($filename, $image->encode());

        $user->update(['avatar' => $filename]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'avatar_url' => Storage::disk('public')->url($filename)
            ]
        ]);
    }

    /**
     * Update user language preference.
     */
    public function updateLanguage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|in:ar,en'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $user->update(['language' => $request->language]);

        return response()->json([
            'success' => true,
            'message' => 'Language updated successfully',
            'data' => [
                'language' => $user->language
            ]
        ]);
    }

    /**
     * Get user security settings.
     */
    public function securitySettings(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'phone' => $user->phone,
                'email' => $user->email,
                'is_verified' => $user->is_verified,
                'phone_verified_at' => $user->phone_verified_at,
                'email_verified_at' => $user->email_verified_at,
                'last_login_at' => $user->last_login_at,
                'status' => $user->status
            ]
        ]);
    }

    /**
     * Update user security settings.
     */
    public function updateSecuritySettings(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $user->update($request->only(['email']));

        return response()->json([
            'success' => true,
            'message' => 'Security settings updated successfully'
        ]);
    }

    /**
     * Get user sessions.
     */
    public function sessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $sessions = $user->sessions()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    /**
     * Delete user session.
     */
    public function deleteSession(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $session = $user->sessions()->findOrFail($id);
        
        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'Session deleted successfully'
        ]);
    }

    /**
     * Get user notifications.
     */
    public function notifications(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationRead(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);
        
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete notification.
     */
    public function deleteNotification(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);
        
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Get notification settings.
     */
    public function notificationSettings(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // In a real application, you would have a separate table for notification settings
        $settings = [
            'push_notifications' => true,
            'sms_notifications' => true,
            'email_notifications' => false,
            'transaction_alerts' => true,
            'security_alerts' => true,
            'promotional_notifications' => false
        ];

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Update notification settings.
     */
    public function updateNotificationSettings(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'push_notifications' => 'sometimes|boolean',
            'sms_notifications' => 'sometimes|boolean',
            'email_notifications' => 'sometimes|boolean',
            'transaction_alerts' => 'sometimes|boolean',
            'security_alerts' => 'sometimes|boolean',
            'promotional_notifications' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // In a real application, you would save these settings to a database
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated successfully'
        ]);
    }
}
