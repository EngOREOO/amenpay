<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\TransactionManagementController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\SystemSettingsController;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group.
|
*/

// Admin routes (authentication required)
Route::middleware('admin.auth')->group(function () {
    
    // Dashboard routes
    Route::prefix('dashboard')->group(function () {
        Route::get('overview', [DashboardController::class, 'overview']);
        Route::get('real-time-metrics', [DashboardController::class, 'realTimeMetrics']);
        Route::get('transaction-analytics', [DashboardController::class, 'transactionAnalytics']);
        Route::get('user-analytics', [DashboardController::class, 'userAnalytics']);
        Route::get('financial-analytics', [DashboardController::class, 'financialAnalytics']);
    });

    // User management routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('create', [UserManagementController::class, 'create'])->name('admin.users.create');
        Route::post('/', [UserManagementController::class, 'store'])->name('admin.users.store');
        Route::get('{id}', [UserManagementController::class, 'show'])->name('admin.users.show');
        Route::get('{id}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('{id}', [UserManagementController::class, 'update'])->name('admin.users.update');
        Route::put('{id}/status', [UserManagementController::class, 'updateStatus']);
        Route::put('{id}/verify', [UserManagementController::class, 'verifyUser']);
        Route::delete('{id}', [UserManagementController::class, 'destroy']);
        Route::get('{id}/transactions', [UserManagementController::class, 'userTransactions']);
        Route::get('{id}/wallet', [UserManagementController::class, 'userWallet']);
        Route::get('{id}/cards', [UserManagementController::class, 'userCards']);
        Route::get('{id}/sessions', [UserManagementController::class, 'userSessions']);
        Route::post('export', [UserManagementController::class, 'exportUsers']);
        Route::post('bulk-actions', [UserManagementController::class, 'bulkActions']);
        Route::get('{id}/transactions/view', [UserManagementController::class, 'viewTransactions'])->name('admin.users.transactions.view');
        Route::get('{id}/cards/view', [UserManagementController::class, 'viewCards'])->name('admin.users.cards.view');
        Route::post('{id}/suspend', [UserManagementController::class, 'suspendUser'])->name('admin.users.suspend');
    });

    // KYC Verification routes
    Route::prefix('kyc')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\KycController::class, 'index'])->name('admin.kyc.index');
        Route::get('{id}', [App\Http\Controllers\Admin\KycController::class, 'show'])->name('admin.kyc.show');
        Route::get('{id}/review', [App\Http\Controllers\Admin\KycController::class, 'review'])->name('admin.kyc.review');
        Route::post('{id}/approve', [App\Http\Controllers\Admin\KycController::class, 'approve'])->name('admin.kyc.approve');
        Route::post('{id}/reject', [App\Http\Controllers\Admin\KycController::class, 'reject'])->name('admin.kyc.reject');
    });

    // Transaction management routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionManagementController::class, 'index'])->name('admin.transactions.index');
        Route::get('create', [TransactionManagementController::class, 'create'])->name('admin.transactions.create');
        Route::get('{id}', [TransactionManagementController::class, 'show'])->name('admin.transactions.show');
        Route::get('{id}/edit', [TransactionManagementController::class, 'edit'])->name('admin.transactions.edit');
        Route::put('{id}/status', [TransactionManagementController::class, 'updateStatus']);
        Route::post('{id}/approve', [TransactionManagementController::class, 'approveTransaction']);
        Route::post('{id}/reject', [TransactionManagementController::class, 'rejectTransaction']);
        Route::post('{id}/refund', [TransactionManagementController::class, 'refundTransaction']);
        Route::get('fraud-alerts', [TransactionManagementController::class, 'fraudAlerts']);
        Route::get('disputes', [TransactionManagementController::class, 'disputes']);
        Route::post('export', [TransactionManagementController::class, 'exportTransactions']);
        Route::post('bulk-actions', [TransactionManagementController::class, 'bulkActions']);
    });

    // System administration routes
    Route::prefix('system')->group(function () {
        // Category management
        Route::get('categories', [SystemController::class, 'categoriesView'])->name('admin.system.categories');
        Route::post('categories', [SystemController::class, 'createCategory']);
        Route::put('categories/{id}', [SystemController::class, 'updateCategory']);
        Route::delete('categories/{id}', [SystemController::class, 'deleteCategory']);
        
        // Notification management
        Route::get('notifications', [SystemController::class, 'notificationsView'])->name('admin.system.notifications');
        Route::post('notifications', [SystemController::class, 'createNotification']);
        Route::put('notifications/{id}', [SystemController::class, 'updateNotification']);
        Route::delete('notifications/{id}', [SystemController::class, 'deleteNotification']);
        Route::post('notifications/broadcast', [SystemController::class, 'broadcastNotification']);
        
        // System settings
        Route::get('settings', [SystemSettingsController::class, 'index'])->name('admin.system.settings');
        Route::put('settings', [SystemController::class, 'updateSettings']);
        
        // API key management
        Route::get('api-keys', [SystemController::class, 'apiKeys']);
        Route::post('api-keys', [SystemController::class, 'createApiKey']);
        Route::delete('api-keys/{id}', [SystemController::class, 'deleteApiKey']);
        
        // Log monitoring
        Route::get('logs', [SystemController::class, 'logsView'])->name('admin.system.logs');
        Route::get('logs/error', [SystemController::class, 'errorLogs']);
        Route::get('logs/access', [SystemController::class, 'accessLogs']);
        
        // Backup management
        Route::get('backups', [SystemController::class, 'backups']);
        Route::post('backups/create', [SystemController::class, 'createBackup']);
        Route::post('backups/{id}/restore', [SystemController::class, 'restoreBackup']);
        Route::delete('backups/{id}', [SystemController::class, 'deleteBackup']);
    });

    // Security & Compliance routes
    Route::prefix('security')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('admin.security.index');
        Route::get('fraud-monitoring', [App\Http\Controllers\Admin\SecurityController::class, 'fraudMonitoring'])->name('admin.security.fraud-monitoring');
        Route::get('compliance', [App\Http\Controllers\Admin\SecurityController::class, 'compliance'])->name('admin.security.compliance');
    });

    // Reports routes
    Route::prefix('reports')->group(function () {
        Route::get('financial', [SystemController::class, 'financialReport'])->name('admin.reports.financial');
        Route::get('user-activity', [SystemController::class, 'userActivityReport'])->name('admin.reports.user-activity');
        Route::get('transaction-summary', [SystemController::class, 'transactionSummaryReport'])->name('admin.reports.transaction-summary');
        Route::get('revenue', [SystemController::class, 'revenueReport'])->name('admin.reports.revenue');
        Route::get('compliance', [SystemController::class, 'complianceReport'])->name('admin.reports.compliance');
        Route::post('export', [SystemController::class, 'exportReport']);
    });
});
