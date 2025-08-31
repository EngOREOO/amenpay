@extends('layouts.admin')

@section('title', 'AI Analytics - Predictions')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">AI Predictions</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Advanced AI-powered financial predictions and forecasting</p>
    </div>

    <!-- Prediction Models Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Fraud Detection</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Real-time transaction analysis</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600">98.7%</div>
                    <div class="text-sm text-gray-500">Accuracy</div>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 98.7%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Risk Assessment</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Portfolio risk prediction</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">94.2%</div>
                    <div class="text-sm text-gray-500">Accuracy</div>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 94.2%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Forecasting</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Financial trend prediction</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-purple-600">91.5%</div>
                    <div class="text-sm text-gray-500">Accuracy</div>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 91.5%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prediction Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Transaction Volume Prediction</h3>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                <p class="text-gray-500 dark:text-gray-400">Chart.js Integration Required</p>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p>AI predicts transaction volume will increase by 15% in the next 30 days</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Fraud Risk Timeline</h3>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                <p class="text-gray-500 dark:text-gray-400">Chart.js Integration Required</p>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p>Risk assessment shows peak fraud activity during holiday seasons</p>
            </div>
        </div>
    </div>

    <!-- Recent Predictions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent AI Predictions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prediction Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Confidence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Predicted Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timeframe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Revenue Growth</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">95.2%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">+18.5%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Next Quarter</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">User Growth</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">92.8%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">+12.3%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Next Month</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Fraud Risk</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">89.1%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Medium</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Next Week</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Monitoring</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection




