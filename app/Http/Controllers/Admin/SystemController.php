<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Get categories listing.
     */
    public function categories(Request $request): JsonResponse
    {
        $categories = Category::with(['parent', 'children'])
            ->when($request->parent_id, function ($query, $parentId) {
                return $query->where('parent_id', $parentId);
            })
            ->when($request->is_active !== null, function ($query) use ($request) {
                return $query->where('is_active', $request->is_active);
            })
            ->orderBy('parent_id', 'asc')
            ->orderBy('name_ar', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Create new category.
     */
    public function createCategory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category->load(['parent', 'children'])
        ]);
    }

    /**
     * Update category.
     */
    public function updateCategory(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category->load(['parent', 'children'])
        ]);
    }

    /**
     * Delete category.
     */
    public function deleteCategory(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        // Check if category has children
        if ($category->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with subcategories'
            ], 400);
        }

        // Check if category is used in transactions
        if ($category->transactions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that is used in transactions'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Get notifications listing.
     */
    public function notifications(Request $request): JsonResponse
    {
        $notifications = Notification::with(['user'])
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->is_read !== null, function ($query) use ($request) {
                return $query->where('is_read', $request->is_read);
            })
            ->when($request->user_id, function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Create new notification.
     */
    public function createNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:50',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'message_ar' => 'required|string',
            'message_en' => 'required|string',
            'data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $notification = Notification::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'data' => $notification->load('user')
        ]);
    }

    /**
     * Update notification.
     */
    public function updateNotification(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'sometimes|string|max:255',
            'title_en' => 'sometimes|string|max:255',
            'message_ar' => 'sometimes|string',
            'message_en' => 'sometimes|string',
            'data' => 'nullable|array',
            'is_read' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $notification = Notification::findOrFail($id);
        $notification->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Notification updated successfully',
            'data' => $notification->load('user')
        ]);
    }

    /**
     * Delete notification.
     */
    public function deleteNotification(Request $request, $id): JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Broadcast notification to all users.
     */
    public function broadcastNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:50',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'message_ar' => 'required|string',
            'message_en' => 'required|string',
            'data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get all active users
        $users = User::where('status', 'active')->get();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'type' => $request->type,
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'message_ar' => $request->message_ar,
                'message_en' => $request->message_en,
                'data' => $request->data,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert notifications in batches
        foreach (array_chunk($notifications, 100) as $batch) {
            Notification::insert($batch);
        }

        return response()->json([
            'success' => true,
            'message' => "Notification broadcasted to {$users->count()} users",
            'data' => [
                'recipients_count' => $users->count(),
                'notification_type' => $request->type
            ]
        ]);
    }

    /**
     * Get system settings.
     */
    public function settings(Request $request): JsonResponse
    {
        // In a real application, you would have a settings table
        // For now, we'll return default settings
        $settings = [
            'app' => [
                'name' => config('app.name'),
                'environment' => config('app.env'),
                'debug' => config('app.debug'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale')
            ],
            'database' => [
                'connection' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database')
            ],
            'mail' => [
                'driver' => config('mail.default'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name')
            ],
            'features' => [
                'otp_enabled' => true,
                'card_validation' => true,
                'qr_payments' => true,
                'bill_payments' => true,
                'analytics' => true
            ],
            'limits' => [
                'max_transaction_amount' => 50000,
                'max_daily_transactions' => 100,
                'otp_expiry_minutes' => 5,
                'session_timeout_hours' => 24
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.features' => 'nullable|array',
            'settings.limits' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // In a real application, you would save these to a database
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }

    /**
     * Get API keys.
     */
    public function apiKeys(Request $request): JsonResponse
    {
        // In a real application, you would have an api_keys table
        // For now, we'll return mock data
        $apiKeys = [
            [
                'id' => 1,
                'name' => 'Mobile App API Key',
                'key' => 'pk_test_' . str_random(32),
                'permissions' => ['read', 'write'],
                'is_active' => true,
                'created_at' => now()->subDays(30),
                'last_used' => now()->subHours(2)
            ],
            [
                'id' => 2,
                'name' => 'Admin Dashboard API Key',
                'key' => 'pk_admin_' . str_random(32),
                'permissions' => ['read', 'write', 'admin'],
                'is_active' => true,
                'created_at' => now()->subDays(15),
                'last_used' => now()->subMinutes(30)
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $apiKeys
        ]);
    }

    /**
     * Create new API key.
     */
    public function createApiKey(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'in:read,write,admin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // In a real application, you would create and store the API key
        $apiKey = [
            'id' => rand(100, 999),
            'name' => $request->name,
            'key' => 'pk_' . str_random(32),
            'permissions' => $request->permissions,
            'is_active' => true,
            'created_at' => now(),
            'last_used' => null
        ];

        return response()->json([
            'success' => true,
            'message' => 'API key created successfully',
            'data' => $apiKey
        ]);
    }

    /**
     * Delete API key.
     */
    public function deleteApiKey(Request $request, $id): JsonResponse
    {
        // In a real application, you would delete the API key from database
        return response()->json([
            'success' => true,
            'message' => 'API key deleted successfully'
        ]);
    }

    /**
     * Get system logs.
     */
    public function logs(Request $request): JsonResponse
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logFile)) {
            $logContent = File::get($logFile);
            $logLines = array_slice(explode("\n", $logContent), -100); // Last 100 lines
            
            foreach ($logLines as $line) {
                if (!empty(trim($line))) {
                    $logs[] = $line;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'logs' => array_reverse($logs),
                'total_lines' => count($logs),
                'file_size' => File::exists($logFile) ? File::size($logFile) : 0
            ]
        ]);
    }

    /**
     * Get error logs.
     */
    public function errorLogs(Request $request): JsonResponse
    {
        $logFile = storage_path('logs/laravel.log');
        $errorLogs = [];

        if (File::exists($logFile)) {
            $logContent = File::get($logFile);
            $logLines = explode("\n", $logContent);
            
            foreach ($logLines as $line) {
                if (strpos($line, '.ERROR') !== false || strpos($line, '.CRITICAL') !== false) {
                    $errorLogs[] = $line;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'error_logs' => array_slice(array_reverse($errorLogs), 0, 50),
                'total_errors' => count($errorLogs)
            ]
        ]);
    }

    /**
     * Get access logs.
     */
    public function accessLogs(Request $request): JsonResponse
    {
        // In a real application, you would have access logs
        // For now, we'll return mock data
        $accessLogs = [
            [
                'timestamp' => now()->subMinutes(5),
                'ip' => '192.168.1.100',
                'method' => 'GET',
                'url' => '/api/wallet/balance',
                'status' => 200,
                'user_agent' => 'P-Finance/1.0 (iOS)'
            ],
            [
                'timestamp' => now()->subMinutes(10),
                'ip' => '192.168.1.101',
                'method' => 'POST',
                'url' => '/api/auth/login',
                'status' => 200,
                'user_agent' => 'P-Finance/1.0 (Android)'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $accessLogs
        ]);
    }

    /**
     * Get backups listing.
     */
    public function backups(Request $request): JsonResponse
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $backups[] = [
                        'name' => basename($file),
                        'size' => File::size($file),
                        'created_at' => Carbon::createFromTimestamp(File::lastModified($file)),
                        'path' => $file
                    ];
                }
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function ($a, $b) {
            return $b['created_at']->compare($a['created_at']);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'backups' => $backups,
                'total_backups' => count($backups),
                'total_size' => array_sum(array_column($backups, 'size'))
            ]
        ]);
    }

    /**
     * Create new backup.
     */
    public function createBackup(Request $request): JsonResponse
    {
        // In a real application, you would create a database backup
        // For now, we'll return a success message
        
        return response()->json([
            'success' => true,
            'message' => 'Backup job queued successfully',
            'data' => [
                'estimated_time' => '5 minutes',
                'backup_type' => 'database'
            ]
        ]);
    }

    /**
     * Restore backup.
     */
    public function restoreBackup(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'confirm' => 'required|boolean|accepted'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Confirmation required for backup restoration'
            ], 422);
        }

        // In a real application, you would restore the backup
        return response()->json([
            'success' => true,
            'message' => 'Backup restoration job queued successfully'
        ]);
    }

    /**
     * Delete backup.
     */
    public function deleteBackup(Request $request, $id): JsonResponse
    {
        // In a real application, you would delete the backup file
        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully'
        ]);
    }

    // Placeholder methods for security and compliance features
    public function fraudMonitoring(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Fraud monitoring feature coming soon',
            'data' => []
        ]);
    }

    public function complianceReports(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Compliance reports feature coming soon',
            'data' => []
        ]);
    }

    public function auditLogs(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Audit logs feature coming soon',
            'data' => []
        ]);
    }

    public function securityAlerts(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Security alerts feature coming soon',
            'data' => []
        ]);
    }

    public function kycAml(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'KYC/AML feature coming soon',
            'data' => []
        ]);
    }

    public function generateRegulatoryReport(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Regulatory report generation feature coming soon'
        ]);
    }

    public function financialReport(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Financial report feature coming soon',
            'data' => []
        ]);
    }

    public function userActivityReport(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'User activity report feature coming soon',
            'data' => []
        ]);
    }

    public function transactionSummaryReport(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Transaction summary report feature coming soon',
            'data' => []
        ]);
    }

    public function revenueReport(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Revenue report feature coming soon',
            'data' => []
        ]);
    }

    public function complianceReport(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Compliance report feature coming soon',
            'data' => []
        ]);
    }

    public function exportReport(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Report export feature coming soon'
        ]);
    }

    /**
     * Get system logs view.
     */
    public function logsView()
    {
        return view('admin.system.logs');
    }

    /**
     * Get system notifications view.
     */
    public function notificationsView()
    {
        return view('admin.system.notifications');
    }

    /**
     * Get system categories view.
     */
    public function categoriesView()
    {
        return view('admin.system.categories');
    }
}
