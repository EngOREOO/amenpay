@extends('layouts.admin')

@section('title', 'Payment Gateways')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Gateways</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage and monitor payment gateway integrations</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.payment-gateways.create') }}" 
                   class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Add Gateway
                </a>
            </div>
        </div>
    </div>

    <!-- Gateway Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Gateways</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">8</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Gateways</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">6</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Volume</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">SAR 1.2M</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Success Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">98.7%</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Gateways Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg animate-slide-up">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gateway</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Transactions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Success Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-blue-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">S</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Stripe</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">stripe.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Credit Card
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">18,456</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">99.2%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.payment-gateways.show', 1) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                                    <a href="{{ route('admin.payment-gateways.edit', 1) }}" 
                                       class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Edit</a>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-green-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">P</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">PayPal</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">paypal.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Digital Wallet
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">12,789</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">98.7%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.payment-gateways.show', 2) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                                    <a href="{{ route('admin.payment-gateways.edit', 2) }}" 
                                       class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Edit</a>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-purple-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">M</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Mada</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">mada.com.sa</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    Local Card
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">8,234</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">97.9%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.payment-gateways.show', 3) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                                    <a href="{{ route('admin.payment-gateways.edit', 3) }}" 
                                       class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Edit</a>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-yellow-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">A</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Apple Pay</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">apple.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Mobile Pay
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Inactive
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">2,156</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">95.3%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.payment-gateways.show', 4) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                                    <a href="{{ route('admin.payment-gateways.edit', 4) }}" 
                                       class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Edit</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
