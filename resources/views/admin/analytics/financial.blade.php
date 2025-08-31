@extends('layouts.admin')

@section('title', 'Financial Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Financial Analytics</h1>
                <p class="text-gray-600 dark:text-gray-400">Comprehensive financial insights and performance metrics</p>
            </div>
            <div class="flex items-center space-x-4">
                <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="7">Last 7 Days</option>
                    <option value="30" selected>Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last Year</option>
                </select>
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">SAR 2.4M</p>
                    <p class="text-sm text-green-600 dark:text-green-400">+12.5% from last month</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Transaction Volume -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Transaction Volume</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">45.2K</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">+8.3% from last month</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Transaction -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Transaction</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">SAR 53.2</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">+2.1% from last month</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Success Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">98.7%</p>
                    <p class="text-sm text-green-600 dark:text-green-400">+0.3% from last month</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenue Trend</h3>
            <div class="h-64 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Chart.js integration needed</p>
                    <p class="text-sm text-gray-400">Revenue over time visualization</p>
                </div>
            </div>
        </div>

        <!-- Transaction Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Transaction Distribution</h3>
            <div class="h-64 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.001 9.001 0 0120.488 9z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Chart.js integration needed</p>
                    <p class="text-sm text-gray-400">Transaction type distribution</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Metrics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">98.7%</span>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white">Uptime</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">System availability</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">2.1s</span>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white">Response Time</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Average API response</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">99.9%</span>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white">Accuracy</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Transaction accuracy</p>
            </div>
        </div>
    </div>
</div>
@endsection
