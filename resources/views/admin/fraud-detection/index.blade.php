@extends('layouts.admin')

@section('title', 'Fraud Detection')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fraud Detection</h1>
                <p class="text-gray-600 dark:text-gray-400">Monitor and manage fraud detection systems</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    View High Risk Alerts
                </button>
            </div>
        </div>
    </div>

    <!-- Fraud Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Alerts</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">156</p>
                    <p class="text-sm text-red-600 dark:text-red-400">+23 from yesterday</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- High Risk Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">High Risk</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">23</p>
                    <p class="text-sm text-red-600 dark:text-red-400">Requires immediate action</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Medium Risk Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Medium Risk</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">67</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Review within 24h</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Risk Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Risk</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">66</p>
                    <p class="text-sm text-green-600 dark:text-green-400">Monitor only</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Fraud Detection Rules -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active Detection Rules</h3>
            <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                Add Rule
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Unusual Transaction Amount</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Detects transactions above normal thresholds</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Triggered: 45 times today</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Multiple Failed Logins</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Detects suspicious login patterns</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Triggered: 23 times today</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Geographic Anomaly</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Detects transactions from unusual locations</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Triggered: 12 times today</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Device Fingerprinting</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Detects new device usage patterns</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Triggered: 34 times today</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Velocity Monitoring</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Detects rapid transaction sequences</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Triggered: 18 times today</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Behavioral Analysis</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">AI-powered behavior pattern detection</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Triggered: 29 times today</div>
            </div>
        </div>
    </div>

    <!-- Recent Fraud Alerts -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Fraud Alerts</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alert</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Risk Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Triggered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Unusual Transaction Amount</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">SAR 15,000</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <img class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600" 
                                         src="https://ui-avatars.com/api/?name=Ahmed+Ali&background=3b82f6&color=fff" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Ahmed Ali</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">+966500000001</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                High Risk
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">2 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Under Review
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">Review</button>
                                <button class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Approve</button>
                                <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Block</button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Multiple Failed Logins</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">5 attempts from new IP</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <img class="h-8 w-8 rounded-full bg-gradient-to-r from-green-500 to-blue-600" 
                                         src="https://ui-avatars.com/api/?name=Sarah+Ahmed&background=10b981&color=fff" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Sarah Ahmed</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">+966500000002</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Medium Risk
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">4 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Resolved
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
