@extends('layouts.admin')

@section('title', 'Edit Payment Gateway')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Payment Gateway</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Modify payment gateway settings and configuration</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.payment-gateways.show', $id) }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                    View Gateway
                </a>
                <a href="{{ route('admin.payment-gateways.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                    Back to Gateways
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gateway Information</h3>
            </div>
            <div class="p-6">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gateway Name</label>
                            <input type="text" value="Stripe" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="e.g., Stripe, PayPal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gateway Type</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option selected>Credit Card</option>
                                <option>Digital Wallet</option>
                                <option>Bank Transfer</option>
                                <option>Cryptocurrency</option>
                                <option>Local Payment</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">API Key</label>
                            <input type="text" value="pk_test_..." class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter API key">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Secret Key</label>
                            <input type="password" value="sk_test_..." class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter secret key">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction Fee (%)</label>
                            <input type="number" step="0.01" value="2.9" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="2.9">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fixed Fee</label>
                            <input type="number" step="0.01" value="0.30" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="0.30">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Processing Time</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option selected>Instant</option>
                                <option>1-2 Business Days</option>
                                <option>3-5 Business Days</option>
                                <option>1 Week</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supported Currencies</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">USD</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">EUR</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">GBP</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">AED</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">CAD</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supported Countries</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">United States</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">United Kingdom</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Germany</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">France</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Canada</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Webhook URL</label>
                        <input type="url" value="https://yourdomain.com/webhooks/stripe" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="https://yourdomain.com/webhooks/payment">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Describe the payment gateway and its features">Leading payment processor for online businesses, supporting major credit cards and digital wallets.</textarea>
                    </div>

                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable this gateway</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable test mode</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Update Gateway</button>
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
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Delete Payment Gateway</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Once you delete a payment gateway, there is no going back. Please be certain.</p>
                    </div>
                    <button type="button" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                        Delete Gateway
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




