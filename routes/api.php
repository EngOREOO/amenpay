<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\GoalsController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\NotificationServiceController;
use App\Http\Controllers\Api\FraudDetectionController;
use App\Http\Controllers\Api\KycVerificationController;
use App\Http\Controllers\Api\RegulatoryReportController;
use App\Http\Controllers\Api\AiAnalyticsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health Check Route
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'message' => 'P-Finance Backend is running successfully!',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'features' => [
            'ai_fraud_detection' => true,
            'kyc_verification' => true,
            'regulatory_reporting' => true,
            'predictive_analytics' => true,
            'audit_logging' => true
        ]
    ]);
});

// Test route
Route::get('test', [AuthController::class, 'test']);

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('login-with-otp', [AuthController::class, 'loginWithOtp']);
    Route::post('resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Protected routes (authentication required)
Route::middleware('api.auth')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
    });

    // User management routes
    Route::prefix('user')->group(function () {
        Route::get('profile', [UserController::class, 'profile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::post('upload-avatar', [UserController::class, 'uploadAvatar']);
        Route::put('change-password', [AuthController::class, 'changePassword']);
        Route::put('language', [UserController::class, 'updateLanguage']);
        Route::get('security-settings', [UserController::class, 'securitySettings']);
        Route::put('security-settings', [UserController::class, 'updateSecuritySettings']);
        Route::get('sessions', [UserController::class, 'sessions']);
        Route::delete('sessions/{id}', [UserController::class, 'deleteSession']);
    });

    // Wallet routes
    Route::prefix('wallet')->group(function () {
        Route::get('balance', [WalletController::class, 'balance']);
        Route::get('transactions', [WalletController::class, 'transactions']);
        Route::post('transfer', [WalletController::class, 'transfer']);
        Route::get('analytics', [WalletController::class, 'analytics']);
        Route::get('statement', [WalletController::class, 'statement']);
        Route::post('deposit', [WalletController::class, 'deposit']);
        Route::post('withdrawal', [WalletController::class, 'withdrawal']);
    });

    // Card management routes
    Route::prefix('cards')->group(function () {
        Route::get('/', [CardController::class, 'index']);
        Route::post('/', [CardController::class, 'store']);
        Route::put('{id}', [CardController::class, 'update']);
        Route::delete('{id}', [CardController::class, 'destroy']);
        Route::post('validate', [CardController::class, 'validate']);
        Route::put('{id}/default', [CardController::class, 'setDefault']);
    });

    // Transaction routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('{id}', [TransactionController::class, 'show']);
        Route::post('process', [TransactionController::class, 'process']);
        Route::post('refund', [TransactionController::class, 'refund']);
        Route::get('status/{id}', [TransactionController::class, 'status']);
    });

    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::post('process', [TransactionController::class, 'processPayment']);
        Route::get('categories', [TransactionController::class, 'categories']);
        Route::post('bill-payment', [TransactionController::class, 'billPayment']);
        Route::post('qr-payment', [TransactionController::class, 'qrPayment']);
        Route::get('history', [TransactionController::class, 'paymentHistory']);
    });

    // Analytics and insights routes
    Route::prefix('analytics')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard']);
        Route::get('/insights', [AnalyticsController::class, 'insights']);
        Route::get('/patterns', [AnalyticsController::class, 'patterns']);
        Route::get('/predictions', [AnalyticsController::class, 'predictions']);
        Route::get('/recommendations', [AnalyticsController::class, 'recommendations']);
        Route::get('/anomalies', [AnalyticsController::class, 'anomalies']);
    });

    // Budget management routes
    Route::prefix('budgets')->group(function () {
        Route::get('/', [BudgetController::class, 'index']);
        Route::post('/', [BudgetController::class, 'store']);
        Route::get('/overview', [BudgetController::class, 'overview']);
        Route::get('/budget-vs-actual', [BudgetController::class, 'budgetVsActual']);
        Route::get('/alerts', [BudgetController::class, 'alerts']);
        Route::get('/{id}', [BudgetController::class, 'show']);
        Route::put('/{id}', [BudgetController::class, 'update']);
        Route::delete('/{id}', [BudgetController::class, 'destroy']);
        Route::post('/{id}/extend', [BudgetController::class, 'extend']);
        Route::post('/{id}/reset', [BudgetController::class, 'reset']);
    });

    // Goals management routes
    Route::prefix('goals')->group(function () {
        Route::get('/', [GoalsController::class, 'index']);
        Route::post('/', [GoalsController::class, 'store']);
        Route::get('/overview', [GoalsController::class, 'overview']);
        Route::get('/alerts', [GoalsController::class, 'alerts']);
        Route::get('/recommendations', [GoalsController::class, 'recommendations']);
        Route::get('/{id}', [GoalsController::class, 'show']);
        Route::put('/{id}', [GoalsController::class, 'update']);
        Route::delete('/{id}', [GoalsController::class, 'destroy']);
        Route::post('/{id}/add-amount', [GoalsController::class, 'addAmount']);
        Route::put('/{id}/status', [GoalsController::class, 'updateStatus']);
        Route::post('/{id}/extend-date', [GoalsController::class, 'extendTargetDate']);
        Route::post('/{id}/milestones', [GoalsController::class, 'addMilestone']);
        Route::post('/{id}/milestones/{milestone_id}/achieve', [GoalsController::class, 'achieveMilestone']);
    });

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [UserController::class, 'notifications']);
        Route::put('{id}/read', [UserController::class, 'markNotificationRead']);
        Route::put('read-all', [UserController::class, 'markAllNotificationsRead']);
        Route::delete('{id}', [UserController::class, 'deleteNotification']);
        Route::get('settings', [UserController::class, 'notificationSettings']);
        Route::put('settings', [UserController::class, 'updateNotificationSettings']);
    });

    // Notification service routes
    Route::prefix('notification-service')->group(function () {
        Route::post('devices/register', [NotificationServiceController::class, 'registerDevice']);
        Route::post('devices/unregister', [NotificationServiceController::class, 'unregisterDevice']);
        Route::put('devices/preferences', [NotificationServiceController::class, 'updateDevicePreferences']);
        Route::get('devices', [NotificationServiceController::class, 'getUserDevices']);
        Route::get('announcements', [NotificationServiceController::class, 'getAnnouncements']);
        Route::post('announcements/{id}/acknowledge', [NotificationServiceController::class, 'acknowledgeAnnouncement']);
        Route::post('test-notification', [NotificationServiceController::class, 'sendTestNotification']);
        Route::get('delivery-status', [NotificationServiceController::class, 'getDeliveryStatus']);
        Route::get('statistics', [NotificationServiceController::class, 'getServiceStatistics']);
    });

    // AI-Powered Fraud Detection Routes
    Route::prefix('fraud-detection')->group(function () {
        Route::get('/statistics', [FraudDetectionController::class, 'getStatistics']);
        Route::get('/trends', [FraudDetectionController::class, 'getTrends']);
        Route::get('/critical', [FraudDetectionController::class, 'getCritical']);
        Route::get('/high-risk', [FraudDetectionController::class, 'getHighRisk']);
        Route::get('/under-investigation', [FraudDetectionController::class, 'getUnderInvestigation']);
        Route::get('/user/{userId}', [FraudDetectionController::class, 'getByUser']);
        Route::post('/analyze-transaction/{transactionId}', [FraudDetectionController::class, 'analyzeTransaction']);
        
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/', [FraudDetectionController::class, 'index']);
            Route::get('/{id}', [FraudDetectionController::class, 'show']);
            Route::post('/', [FraudDetectionController::class, 'store']);
            Route::put('/{id}', [FraudDetectionController::class, 'update']);
            Route::post('/{id}/investigating', [FraudDetectionController::class, 'markAsInvestigating']);
            Route::post('/{id}/resolved', [FraudDetectionController::class, 'markAsResolved']);
            Route::post('/{id}/false-positive', [FraudDetectionController::class, 'markAsFalsePositive']);
        });
    });

    // KYC Verification Routes
    Route::prefix('kyc')->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/', [KycVerificationController::class, 'index']);
            Route::get('/{id}', [KycVerificationController::class, 'show']);
            Route::post('/', [KycVerificationController::class, 'store']);
            Route::put('/{id}', [KycVerificationController::class, 'update']);
            Route::post('/{id}/submit', [KycVerificationController::class, 'submitForReview']);
            Route::post('/{id}/start-review', [KycVerificationController::class, 'startReview']);
            Route::post('/{id}/approve', [KycVerificationController::class, 'approve']);
            Route::post('/{id}/reject', [KycVerificationController::class, 'reject']);
            Route::get('/user/{userId}', [KycVerificationController::class, 'getByUser']);
            Route::get('/statistics', [KycVerificationController::class, 'getStatistics']);
            Route::get('/trends', [KycVerificationController::class, 'getTrends']);
        });
    });

    // Regulatory Reporting Routes
    Route::prefix('regulatory')->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/', [RegulatoryReportController::class, 'index']);
            Route::get('/{id}', [RegulatoryReportController::class, 'show']);
            Route::post('/', [RegulatoryReportController::class, 'store']);
            Route::put('/{id}', [RegulatoryReportController::class, 'update']);
            Route::post('/{id}/submit', [RegulatoryReportController::class, 'submitReport']);
            Route::post('/{id}/accept', [RegulatoryReportController::class, 'acceptReport']);
            Route::post('/{id}/reject', [RegulatoryReportController::class, 'rejectReport']);
            Route::get('/statistics', [RegulatoryReportController::class, 'getStatistics']);
            Route::get('/trends', [RegulatoryReportController::class, 'getTrends']);
            Route::get('/by-authority/{authority}', [RegulatoryReportController::class, 'getByAuthority']);
            Route::get('/by-type/{type}', [RegulatoryReportController::class, 'getByType']);
        });
    });

    // AI Analytics Routes
    Route::prefix('ai-analytics')->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/', [AiAnalyticsController::class, 'index']);
            Route::get('/{id}', [AiAnalyticsController::class, 'show']);
            Route::post('/', [AiAnalyticsController::class, 'store']);
            Route::put('/{id}', [AiAnalyticsController::class, 'update']);
            Route::get('/user/{userId}', [AiAnalyticsController::class, 'getByUser']);
            Route::get('/predictions/{type}', [AiAnalyticsController::class, 'getPredictions']);
            Route::post('/generate-prediction', [AiAnalyticsController::class, 'generatePrediction']);
            Route::get('/model-performance', [AiAnalyticsController::class, 'getModelPerformance']);
            Route::get('/feature-importance', [AiAnalyticsController::class, 'getFeatureImportance']);
            Route::get('/anomaly-detection', [AiAnalyticsController::class, 'detectAnomalies']);
            Route::get('/trend-analysis', [AiAnalyticsController::class, 'analyzeTrends']);
            Route::get('/recommendations', [AiAnalyticsController::class, 'getRecommendations']);
        });
    });
});
