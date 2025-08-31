@extends('layouts.admin')

@section('title', 'Payment Gateway Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Payment Gateway Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">View and manage payment gateway information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.payment-gateways.edit', $id) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                    Edit Gateway
                </a>
                <a href="{{ route('admin.payment-gateways.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                    Back to Gateways
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Gateway Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gateway Overview</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">Stripe</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gateway Name</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">Credit Card</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gateway Type</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">Active</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Status</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gateway Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Gateway Name</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Stripe</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Gateway Type</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Credit Card</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Leading payment processor for online businesses, supporting major credit cards and digital wallets.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created Date</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">January 15, 2024</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">March 20, 2024</p>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configuration</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">API Key</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">pk_test_...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Secret Key</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">sk_test_...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Webhook URL</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">https://yourdomain.com/webhooks/stripe</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Test Mode</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Enabled</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Structure -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Fee Structure</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">2.9%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Transaction Fee</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">$0.30</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Fixed Fee</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">Instant</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Processing Time</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supported Features -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- Supported Currencies -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supported Currencies</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">USD</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">EUR</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">GBP</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">CAD</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">AUD</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">JPY</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supported Countries -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supported Countries</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">United States</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">United Kingdom</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">Germany</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">France</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">Canada</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-900 dark:text-white">Australia</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance Metrics</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">1,247</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Transactions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">98.7%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Success Rate</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">$45,230</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Volume</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">2.3s</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Avg. Response</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




