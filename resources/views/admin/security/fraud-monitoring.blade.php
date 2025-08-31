@extends('layouts.admin')

@section('title', 'Security - Fraud Monitoring')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Fraud Monitoring</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Real-time fraud detection and monitoring dashboard</p>
    </div>

    <!-- Fraud Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Threats</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">23</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Suspicious Activities</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">156</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Blocked Attempts</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">89</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">System Status</p>
                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">Secure</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Monitoring -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Threat Detection Timeline</h3>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                <p class="text-gray-500 dark:text-gray-400">Chart.js Integration Required</p>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p>Real-time monitoring shows increased activity during peak hours</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Geographic Risk Map</h3>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                <p class="text-gray-500 dark:text-gray-400">Map Integration Required</p>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p>High-risk regions identified and monitored</p>
            </div>
        </div>
    </div>

    <!-- Active Threats -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active Threats</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Threat ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Risk Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#THREAT-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">DDoS Attack</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Critical</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">192.168.1.100</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">2 min ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Mitigating</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Block IP</button>
                            <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Investigate</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#THREAT-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">SQL Injection</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">High</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">203.45.67.89</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">5 min ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Blocked</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Investigate</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#THREAT-003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Brute Force</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Medium</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">45.67.89.123</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">12 min ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Resolved</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Review</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Vulnerabilities</h3>
            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Critical</span>
                    <span class="font-medium text-red-600">2</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">High</span>
                    <span class="font-medium text-orange-600">5</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Medium</span>
                    <span class="font-medium text-yellow-600">12</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Low</span>
                    <span class="font-medium text-blue-600">28</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Security Score</h3>
            <div class="text-center">
                <div class="text-4xl font-bold text-green-600 dark:text-green-400">87</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Out of 100</div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: 87%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Alerts</h3>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">DDoS attack detected</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Multiple login attempts</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Security scan completed</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Firewall updated</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




