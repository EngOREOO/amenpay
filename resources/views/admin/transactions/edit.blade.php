@extends('layouts.admin')

@section('title', 'Edit Transaction')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Transaction</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Modify transaction information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                    View Transaction
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                    Back to Transactions
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Information</h3>
            </div>
            <div class="p-6">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="{{ $transaction->user->id }}" selected>{{ $transaction->user->name }} ({{ $transaction->user->email }})</option>
                                <option value="2">Jane Smith (jane.smith@email.com)</option>
                                <option value="3">Mike Wilson (mike.wilson@email.com)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction Type</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="deposit" {{ $transaction->type === 'deposit' ? 'selected' : '' }}>Deposit</option>
                                <option value="withdrawal" {{ $transaction->type === 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                                <option value="transfer" {{ $transaction->type === 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="payment" {{ $transaction->type === 'payment' ? 'selected' : '' }}>Payment</option>
                                <option value="refund" {{ $transaction->type === 'refund' ? 'selected' : '' }}>Refund</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                            <input type="number" step="0.01" value="{{ $transaction->amount }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="USD" {{ $transaction->currency === 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ $transaction->currency === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ $transaction->currency === 'GBP' ? 'selected' : '' }}>GBP</option>
                                <option value="AED" {{ $transaction->currency === 'AED' ? 'selected' : '' }}>AED</option>
                                <option value="CAD" {{ $transaction->currency === 'CAD' ? 'selected' : '' }}>CAD</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Gateway</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="stripe" {{ ($transaction->payment_gateway ?? '') === 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="paypal" {{ ($transaction->payment_gateway ?? '') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="bank_transfer" {{ ($transaction->payment_gateway ?? '') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ ($transaction->payment_gateway ?? '') === 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ $transaction->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter transaction description">{{ $transaction->description ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reference Number</label>
                            <input type="text" value="{{ $transaction->reference_number ?? '' }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter reference number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Risk Score</label>
                            <input type="number" step="0.01" min="0" max="1" value="{{ $transaction->risk_score ?? 0 }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="0.00">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="food_dining" {{ ($transaction->category ?? '') === 'food_dining' ? 'selected' : '' }}>Food & Dining</option>
                                <option value="transportation" {{ ($transaction->category ?? '') === 'transportation' ? 'selected' : '' }}>Transportation</option>
                                <option value="shopping" {{ ($transaction->category ?? '') === 'shopping' ? 'selected' : '' }}>Shopping</option>
                                <option value="utilities" {{ ($transaction->category ?? '') === 'utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="entertainment" {{ ($transaction->category ?? '') === 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                                <option value="healthcare" {{ ($transaction->category ?? '') === 'healthcare' ? 'selected' : '' }}>Healthcare</option>
                                <option value="education" {{ ($transaction->category ?? '') === 'education' ? 'selected' : '' }}>Education</option>
                                <option value="other" {{ ($transaction->category ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                            <input type="text" value="{{ $transaction->tags ?? '' }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter tags separated by commas">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction Fee</label>
                            <input type="number" step="0.01" value="{{ $transaction->fee ?? 0 }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Processing Time</label>
                            <input type="number" value="{{ $transaction->processing_time ?? 0 }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Processing time in seconds">
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" {{ ($transaction->requires_approval ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Require approval</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" {{ ($transaction->send_notification ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send notification</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" {{ ($transaction->high_priority ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">High priority</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Update Transaction</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">Danger Zone</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Delete Transaction</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Once you delete a transaction, there is no going back. Please be certain.</p>
                    </div>
                    <button type="button" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                        Delete Transaction
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection













