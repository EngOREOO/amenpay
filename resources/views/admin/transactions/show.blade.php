@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Transaction Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">View and manage transaction information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                    Edit Transaction
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                    Back to Transactions
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Transaction Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Overview</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">#{{ $transaction->id }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Transaction ID</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $transaction->type }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Type</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $transaction->status }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Status</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $transaction->created_at->diffForHumans() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Created</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Transaction Type</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Amount</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Currency</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->currency }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->status }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->description ?? 'No description provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User ID</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->user->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->user->status }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Details</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Gateway</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->payment_gateway ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reference Number</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->reference_number ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Transaction Fee</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $transaction->currency }} {{ number_format($transaction->fee ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Risk Assessment -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Risk Assessment</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Risk Score</label>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                @php
                                    $riskPercentage = ($transaction->risk_score ?? 0) * 100;
                                    $riskColor = $riskPercentage > 70 ? 'bg-red-600' : ($riskPercentage > 40 ? 'bg-yellow-600' : 'bg-green-600');
                                @endphp
                                <div class="{{ $riskColor }} h-3 rounded-full" style="width: {{ $riskPercentage }}%"></div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $riskPercentage }}% ({{ $transaction->risk_score ?? 0 }})</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Risk Level</label>
                        @php
                            $riskLevel = $riskPercentage > 70 ? 'High' : ($riskPercentage > 40 ? 'Medium' : 'Low');
                            $riskLevelColor = $riskPercentage > 70 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ($riskPercentage > 40 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200');
                        @endphp
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $riskLevelColor }} mt-2">{{ $riskLevel }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Timeline -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Timeline</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Transaction Created</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $transaction->created_at->format('M d, Y \a\t H:i:s') }}</p>
                        </div>
                    </div>
                    @if($transaction->updated_at != $transaction->created_at)
                    <div class="flex items-center space-x-4">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Transaction Updated</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $transaction->updated_at->format('M d, Y \a\t H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-4">
                    @if($transaction->status === 'pending')
                    <button class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                        Approve Transaction
                    </button>
                    <button class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                        Reject Transaction
                    </button>
                    @endif
                    @if($transaction->status === 'completed')
                    <button class="px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700">
                        Refund Transaction
                    </button>
                    @endif
                    <button class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        Export Details
                    </button>
                    <button class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                        Flag for Review
                    </button>
                    <button class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                        Add Note
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection













