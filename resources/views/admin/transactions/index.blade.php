@extends('layouts.admin')

@section('title', 'Transaction Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transaction Management</h1>
                <p class="text-gray-600 dark:text-gray-400">Monitor and manage all transactions</p>
            </div>
            <a href="{{ route('admin.transactions.create') }}" 
               class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                Create Transaction
            </a>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg animate-slide-up">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Transaction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $transaction->id }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->type ?? 'transfer' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $transaction->user->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->user->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    SAR {{ number_format($transaction->amount, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                    {{ ucfirst($transaction->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}" 
                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No transactions found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
