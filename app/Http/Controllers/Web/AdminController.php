<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\FraudDetection;
use App\Models\KycVerification;
use App\Models\RegulatoryReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_transactions' => Transaction::count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'pending_kyc' => KycVerification::where('status', 'pending')->count(),
            'fraud_alerts' => FraudDetection::where('status', 'pending')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();
        $recentFraudAlerts = FraudDetection::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentTransactions', 'recentFraudAlerts'));
    }

    /**
     * Show users management
     */
    public function users()
    {
        $users = User::with('wallet')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Show transactions management
     */
    public function transactions()
    {
        $transactions = Transaction::with('user', 'wallet')->latest()->paginate(20);
        return view('admin.transactions', compact('transactions'));
    }

    /**
     * Show fraud detection management
     */
    public function fraudDetection()
    {
        $fraudDetections = FraudDetection::with('user', 'transaction')->latest()->paginate(20);
        return view('admin.fraud-detection', compact('fraudDetections'));
    }

    /**
     * Show KYC management
     */
    public function kyc()
    {
        $kycVerifications = KycVerification::with('user')->latest()->paginate(20);
        return view('admin.kyc', compact('kycVerifications'));
    }

    /**
     * Show reports management
     */
    public function reports()
    {
        $reports = RegulatoryReport::latest()->paginate(20);
        return view('admin.reports', compact('reports'));
    }

    /**
     * Show system settings
     */
    public function settings()
    {
        return view('admin.settings');
    }
}
