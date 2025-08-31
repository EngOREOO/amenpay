@extends('layouts.app')

@section('title', 'Dashboard - P-Finance')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}!</h1>
            <p class="text-gray-600">Here's what's happening with your finances today.</p>
        </div>

        <!-- Wallet Overview -->
        <div class="bg-white rounded-2xl p-6 card-shadow mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Wallet Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Current Balance</p>
                    <p class="text-3xl font-bold text-gray-900">SAR {{ number_format($wallet->balance, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Wallet Number</p>
                    <p class="text-lg font-medium text-gray-900">{{ $wallet->wallet_number }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ ucfirst($wallet->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="#" class="bg-white p-6 rounded-2xl card-shadow hover-scale text-center">
                <i class="fas fa-plus text-3xl text-blue-600 mb-4"></i>
                <p class="text-lg font-semibold text-gray-900">Add Money</p>
            </a>
            <a href="#" class="bg-white p-6 rounded-2xl card-shadow hover-scale text-center">
                <i class="fas fa-paper-plane text-3xl text-green-600 mb-4"></i>
                <p class="text-lg font-semibold text-gray-900">Send Money</p>
            </a>
            <a href="#" class="bg-white p-6 rounded-2xl card-shadow hover-scale text-center">
                <i class="fas fa-credit-card text-3xl text-purple-600 mb-4"></i>
                <p class="text-lg font-semibold text-gray-900">Add Card</p>
            </a>
            <a href="#" class="bg-white p-6 rounded-2xl card-shadow hover-scale text-center">
                <i class="fas fa-chart-line text-3xl text-orange-600 mb-4"></i>
                <p class="text-lg font-semibold text-gray-900">Analytics</p>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl p-6 card-shadow">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
            @if($recentTransactions->count() > 0)
                <div class="space-y-4">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $transaction->type }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">SAR {{ number_format($transaction->amount, 2) }}</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                @if($transaction->status === 'completed') bg-green-100 text-green-800
                                @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No recent transactions</p>
            @endif
        </div>
    </div>
</div>
@endsection

