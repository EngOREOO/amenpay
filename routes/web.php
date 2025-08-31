<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\TransactionManagementController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\FraudDetectionController;
use App\Http\Controllers\Admin\RegulatoryReportController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AiAnalyticsController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', function () {
    return redirect('/amen-landing');
})->name('home');

Route::get('/amen-landing', function () {
    return response()->file(public_path('amen-landing.html'));
});

// Temporary test routes to bypass authentication
Route::get('/test-admin', function () {
    return view('admin.dashboard', [
        'stats' => [
            'total_users' => 1250,
            'total_transactions' => 5670,
            'total_revenue' => 125000,
            'active_users' => 890
        ],
        'recentTransactions' => []
    ]);
});

Route::get('/test-users', function () {
    return view('admin.users.index', [
        'users' => collect([
            (object) [
                'id' => 1,
                'name' => 'Ahmed Al-Rashid',
                'phone' => '+966501234567',
                'status' => 'active',
                'is_verified' => true,
                'created_at' => now()->subDays(5)
            ],
            (object) [
                'id' => 2,
                'name' => 'Sarah Al-Zahra',
                'phone' => '+966507654321',
                'status' => 'active',
                'is_verified' => false,
                'created_at' => now()->subDays(2)
            ]
        ])
    ]);
});

Route::get('/test-transactions', function () {
    return view('admin.transactions.index', [
        'transactions' => collect([
            (object) [
                'id' => 1,
                'type' => 'transfer',
                'amount' => 500.00,
                'status' => 'completed',
                'created_at' => now()->subHours(2),
                'user' => (object) ['name' => 'Ahmed Al-Rashid', 'phone' => '+966501234567']
            ],
            (object) [
                'id' => 2,
                'type' => 'payment',
                'amount' => 150.00,
                'status' => 'pending',
                'created_at' => now()->subHours(1),
                'user' => (object) ['name' => 'Sarah Al-Zahra', 'phone' => '+966507654321']
            ]
        ])
    ]);
});

// Admin Authentication Routes (no middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Web Routes (for dashboard views) - requires authentication
Route::prefix('admin')->name('admin.')->middleware(['web', 'admin.auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::put('/users/{id}/status', [UserManagementController::class, 'updateStatus'])->name('users.update-status');
    Route::put('/users/{id}/verify', [UserManagementController::class, 'verifyUser'])->name('users.verify');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}/transactions', [UserManagementController::class, 'userTransactions'])->name('users.transactions');
    Route::get('/users/{id}/wallet', [UserManagementController::class, 'userWallet'])->name('users.wallet');
    Route::get('/users/{id}/cards', [UserManagementController::class, 'userCards'])->name('users.cards');
    Route::get('/users/{id}/sessions', [UserManagementController::class, 'userSessions'])->name('users.sessions');
    Route::get('/users/{id}/transactions/view', [UserManagementController::class, 'viewTransactions'])->name('users.transactions.view');
    Route::get('/users/{id}/cards/view', [UserManagementController::class, 'viewCards'])->name('users.cards.view');
    Route::post('/users/{id}/suspend', [UserManagementController::class, 'suspendUser'])->name('users.suspend');
    
    // Transaction Management
    Route::get('/transactions', [TransactionManagementController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionManagementController::class, 'create'])->name('transactions.create');
    Route::get('/transactions/{id}', [TransactionManagementController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{id}/edit', [TransactionManagementController::class, 'edit'])->name('transactions.edit');
    
    // Analytics
    Route::get('/analytics/financial', [AnalyticsController::class, 'financial'])->name('analytics.financial');
    Route::get('/analytics/users', [AnalyticsController::class, 'users'])->name('analytics.users');
    Route::get('/analytics/transactions', [AnalyticsController::class, 'transactions'])->name('analytics.transactions');
    
    // Payment Gateways
    Route::get('/payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::get('/payment-gateways/create', [PaymentGatewayController::class, 'create'])->name('payment-gateways.create');
    Route::get('/payment-gateways/{id}', [PaymentGatewayController::class, 'show'])->name('payment-gateways.show');
    Route::get('/payment-gateways/{id}/edit', [PaymentGatewayController::class, 'edit'])->name('payment-gateways.edit');
    
    // KYC Management
    Route::get('/kyc', [KycController::class, 'index'])->name('kyc.index');
    Route::get('/kyc/{id}', [KycController::class, 'show'])->name('kyc.show');
    Route::get('/kyc/{id}/review', [KycController::class, 'review'])->name('kyc.review');
    
    // Fraud Detection
    Route::get('/fraud-detection', [FraudDetectionController::class, 'index'])->name('fraud-detection.index');
    Route::get('/fraud-detection/alerts', [FraudDetectionController::class, 'alerts'])->name('fraud-detection.alerts');
    Route::get('/fraud-detection/rules', [FraudDetectionController::class, 'rules'])->name('fraud-detection.rules');
    
    // Regulatory Reports
    Route::get('/regulatory-reports', [RegulatoryReportController::class, 'index'])->name('regulatory-reports.index');
    Route::get('/regulatory-reports/create', [RegulatoryReportController::class, 'create'])->name('regulatory-reports.create');
    Route::get('/regulatory-reports/{id}', [RegulatoryReportController::class, 'show'])->name('regulatory-reports.show');
    
    // System Settings
    Route::get('/system/settings', [SystemSettingsController::class, 'index'])->name('system.settings');
    Route::get('/system/general', [SystemSettingsController::class, 'general'])->name('system.general');
    Route::get('/system/security', [SystemSettingsController::class, 'security'])->name('system.security');
    Route::get('/system/notifications', [SystemSettingsController::class, 'notifications'])->name('system.notifications');
    Route::get('/system/integrations', [SystemSettingsController::class, 'integrations'])->name('system.integrations');
    
    // System Settings Update Routes
    Route::put('/system/settings/overview', [SystemSettingsController::class, 'updateOverview'])->name('system.settings.update-overview');
    Route::put('/system/settings/general', [SystemSettingsController::class, 'updateGeneral'])->name('system.settings.update-general');
    Route::put('/system/settings/security', [SystemSettingsController::class, 'updateSecurity'])->name('system.settings.update-security');
    Route::put('/system/settings/notifications', [SystemSettingsController::class, 'updateNotifications'])->name('system.settings.update-notifications');
    Route::put('/system/settings/integrations', [SystemSettingsController::class, 'updateIntegrations'])->name('system.settings.update-integrations');
    Route::get('/system/categories', [SystemController::class, 'categories'])->name('system.categories');
    Route::get('/system/logs', [SystemController::class, 'logs'])->name('system.logs');
    
    // Security & Compliance
    Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
    Route::get('/security/fraud-monitoring', [SecurityController::class, 'fraudMonitoring'])->name('security.fraud-monitoring');
    Route::get('/security/compliance', [SecurityController::class, 'compliance'])->name('security.compliance');
    
    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    
    // AI Analytics
    Route::get('/ai-analytics', [AiAnalyticsController::class, 'index'])->name('ai-analytics.index');
    Route::get('/ai-analytics/predictions', [AiAnalyticsController::class, 'predictions'])->name('ai-analytics.predictions');
    Route::get('/ai-analytics/insights', [AiAnalyticsController::class, 'insights'])->name('ai-analytics.insights');
    
    // Reports
    Route::get('/reports/financial', [SystemController::class, 'financialReport'])->name('reports.financial');
    Route::get('/reports/users', [SystemController::class, 'userActivityReport'])->name('reports.users');
    Route::get('/reports/transactions', [SystemController::class, 'transactionSummaryReport'])->name('reports.transactions');
    Route::get('/reports/revenue', [SystemController::class, 'revenueReport'])->name('reports.revenue');
    Route::get('/reports/compliance', [SystemController::class, 'complianceReport'])->name('reports.compliance');
    
    // Profile
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
    
    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

// Catch-all route for admin dashboard
Route::get('/admin/{any}', function () {
    return redirect()->route('admin.dashboard');
})->where('any', '.*');
