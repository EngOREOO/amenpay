<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_transactions' => Transaction::count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'active_users' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
        ];

        // Get recent transactions
        $recentTransactions = Transaction::with('wallet.user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions'));
    }

    public function overview()
    {
        return response()->json([
            'message' => 'Dashboard overview data'
        ]);
    }

    public function realTimeMetrics()
    {
        return response()->json([
            'message' => 'Real-time metrics data'
        ]);
    }

    public function transactionAnalytics()
    {
        return response()->json([
            'message' => 'Transaction analytics data'
        ]);
    }

    public function userAnalytics()
    {
        return response()->json([
            'message' => 'User analytics data'
        ]);
    }

    public function financialAnalytics()
    {
        return response()->json([
            'message' => 'Financial analytics data'
        ]);
    }
}
