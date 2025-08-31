@extends('layouts.admin')

@section('title', 'User Transactions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Transactions</h1>
                <p class="text-gray-600 dark:text-gray-400">Transaction history for {{ $user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.show', $user->id) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Back to User
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    All Users
                </a>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ $user->phone }}</p>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction History</h3>
        </div>
        
        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    #{{ $transaction->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $transaction->type === 'deposit' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           ($transaction->type === 'withdrawal' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200') }}">
                                        {{ ucfirst($transaction->type ?? 'transfer') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="font-medium {{ $transaction->type === 'deposit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->type === 'deposit' ? '+' : '-' }}SAR {{ number_format($transaction->amount ?? 0, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ ucfirst($transaction->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at ? $transaction->created_at->format('M d, Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            @endif
        @else
            <div class="p-6 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No transactions found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This user hasn't made any transactions yet.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
