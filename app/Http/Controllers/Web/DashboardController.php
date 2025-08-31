<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $recentTransactions = $user->transactions()->latest()->take(5)->get();
        $cards = $user->cards()->where('is_active', true)->get();
        $budgets = $user->budgets()->where('status', 'active')->get();
        $goals = $user->goals()->where('status', 'active')->get();

        return view('dashboard.index', compact(
            'user',
            'wallet',
            'recentTransactions',
            'cards',
            'budgets',
            'goals'
        ));
    }
}
