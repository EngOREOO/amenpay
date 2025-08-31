@extends('layouts.admin')

@section('title', 'User Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Analytics</h1>
                <p class="text-gray-600 dark:text-gray-400">User growth, engagement, and behavior insights</p>
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

    <!-- User Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">12,456</p>
                    <p class="text-sm text-green-600 dark:text-green-400">+8.7% from last month</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">8,234</p>
                    <p class="text-sm text-green-600 dark:text-green-400">66.1% of total users</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Users -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">New Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">1,234</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">+15.2% from last month</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Verified Users -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Verified Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">9,876</p>
                    <p class="text-sm text-green-600 dark:text-green-400">79.3% verification rate</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- User Growth Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Growth Over Time -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Growth Over Time</h3>
            <div class="h-64 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Chart.js integration needed</p>
                    <p class="text-sm text-gray-400">Monthly user growth trend</p>
                </div>
            </div>
        </div>

        <!-- User Demographics -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Demographics</h3>
            <div class="h-64 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.001 9.001 0 0120.488 9z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Chart.js integration needed</p>
                    <p class="text-sm text-gray-400">Age and location distribution</p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Engagement Metrics -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Engagement Metrics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">4.2</span>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white">Avg Sessions</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Per user per month</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">12.5</span>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white">Avg Duration</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Minutes per session</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">67%</span>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white">Retention Rate</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">30-day retention</p>
            </div>
        </div>
    </div>

    <!-- User Activity Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent User Activity</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Transactions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">2 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">45</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                        </td>
                    </tr>
                    <tr>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">1 day ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Inactive
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">23</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
