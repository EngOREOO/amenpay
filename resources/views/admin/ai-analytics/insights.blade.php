@extends('layouts.admin')

@section('title', 'AI Analytics - Insights')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">AI Insights</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Intelligent insights and recommendations powered by AI</p>
    </div>

    <!-- Key Insights Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white ml-3">Revenue Optimization</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">AI analysis suggests implementing dynamic pricing during peak hours could increase revenue by 23%.</p>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Confidence: 94.2%</span>
                <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">View Details</button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white ml-3">User Engagement</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Personalized notifications based on spending patterns could improve user retention by 18%.</p>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Confidence: 91.7%</span>
                <button class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">View Details</button>
            </div>
        </div>
    </div>

    <!-- AI Recommendations -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">AI Recommendations</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-start space-x-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">High Priority</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Implement real-time fraud detection for transactions above $10,000 to reduce risk by 45%.</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="text-xs text-blue-600 dark:text-blue-400">Impact: High</span>
                            <span class="text-xs text-blue-600 dark:text-blue-400">Effort: Medium</span>
                            <button class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Implement</button>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Quick Win</h4>
                        <p class="text-sm text-green-700 dark:text-green-300 mt-1">Add spending category suggestions based on merchant data to improve user experience.</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="text-xs text-green-600 dark:text-green-400">Impact: Medium</span>
                            <span class="text-xs text-green-600 dark:text-green-400">Effort: Low</span>
                            <button class="text-xs text-green-600 dark:text-green-400 hover:underline">Implement</button>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Monitor</h4>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">User churn rate increased by 8% in the last month. Consider implementing loyalty programs.</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="text-xs text-yellow-600 dark:text-yellow-400">Impact: Medium</span>
                            <span class="text-xs text-yellow-600 dark:text-yellow-400">Effort: High</span>
                            <button class="text-xs text-yellow-600 dark:text-yellow-400 hover:underline">Investigate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pattern Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Spending Pattern Analysis</h3>
            <div class="h-48 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center mb-4">
                <p class="text-gray-500 dark:text-gray-400">Chart.js Integration Required</p>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Food & Dining</span>
                    <span class="font-medium text-gray-900 dark:text-white">32%</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Transportation</span>
                    <span class="font-medium text-gray-900 dark:text-white">24%</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Shopping</span>
                    <span class="font-medium text-gray-900 dark:text-white">18%</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Utilities</span>
                    <span class="font-medium text-gray-900 dark:text-white">16%</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Other</span>
                    <span class="font-medium text-gray-900 dark:text-white">10%</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Risk Assessment</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Overall Risk Score</span>
                    <span class="text-lg font-bold text-green-600">Low</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 25%"></div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Transaction Risk</span>
                        <span class="text-green-600">Low</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">User Risk</span>
                        <span class="text-yellow-600">Medium</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">System Risk</span>
                        <span class="text-green-600">Low</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Compliance Risk</span>
                        <span class="text-green-600">Low</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




