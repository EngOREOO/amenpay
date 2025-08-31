@extends('layouts.admin')

@section('title', 'AI Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Analytics</h1>
                <p class="text-gray-600 dark:text-gray-400">AI-powered insights and predictive analytics</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Generate Insights
                </button>
            </div>
        </div>
    </div>

    <!-- AI Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- AI Confidence Score -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">AI Confidence</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">94.2%</p>
                    <p class="text-sm text-green-600 dark:text-green-400">High accuracy</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Predictions Generated -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Predictions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">1,234</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">This month</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Anomalies Detected -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Anomalies</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">67</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">+12 this week</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Model Performance -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Model Performance</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">98.7%</p>
                    <p class="text-sm text-purple-600 dark:text-purple-400">Excellent</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Models Overview -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Active AI Models</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Fraud Detection</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Real-time transaction fraud detection</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Accuracy: 96.8%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: 2 hours ago</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Risk Assessment</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">User risk scoring and classification</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Accuracy: 94.2%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: 1 day ago</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Behavioral Analysis</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">User behavior pattern recognition</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Accuracy: 92.7%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: 6 hours ago</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Predictive Analytics</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Transaction volume and revenue forecasting</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Accuracy: 89.5%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: 12 hours ago</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Customer Segmentation</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">AI-powered customer clustering</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Accuracy: 91.3%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: 1 day ago</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Churn Prediction</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        Training
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Customer churn risk prediction</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Accuracy: 87.2%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: 3 days ago</div>
            </div>
        </div>
    </div>

    <!-- AI Insights and Predictions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent AI Insights -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent AI Insights</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 dark:text-white">Unusual Transaction Pattern Detected</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">AI detected a 15% increase in high-value transactions from new users, suggesting potential fraud activity.</p>
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Confidence: 94.2% • 2 hours ago</div>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 dark:text-white">Revenue Growth Prediction</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">AI predicts 23% revenue growth in Q1 2025 based on current user acquisition trends and market analysis.</p>
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Confidence: 91.7% • 4 hours ago</div>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="flex-shrink-0 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 dark:text-white">Customer Churn Risk Alert</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">AI identified 156 users with high churn risk (78% probability) based on recent activity patterns.</p>
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Confidence: 89.3% • 6 hours ago</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Predictions Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">AI Predictions Overview</h3>
            <div class="h-64 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Chart.js integration needed</p>
                    <p class="text-sm text-gray-400">AI prediction accuracy over time</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Model Performance -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">AI Model Performance Metrics</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Model</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Accuracy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precision</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Recall</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F1 Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-lg bg-red-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Fraud Detection</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Real-time ML model</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">96.8%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">94.2%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">97.1%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">95.6%</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Active
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-lg bg-blue-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Risk Assessment</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Classification model</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">94.2%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">92.8%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">95.1%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">93.9%</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Active
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-lg bg-green-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Behavioral Analysis</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Pattern recognition</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">92.7%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">90.5%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">93.2%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">91.8%</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Active
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
