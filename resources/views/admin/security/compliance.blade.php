@extends('layouts.admin')

@section('title', 'Security - Compliance')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Security Compliance</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Compliance monitoring and regulatory adherence dashboard</p>
    </div>

    <!-- Compliance Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Overall Score</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">94%</p>
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
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Standards Met</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">18/20</p>
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
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Reviews</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">3</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Audit</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">2 days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Standards -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Compliance Standards</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-green-900 dark:text-green-100">ISO 27001</h4>
                            <p class="text-sm text-green-700 dark:text-green-300">Information Security Management</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-green-600 dark:text-green-400">100%</div>
                        <div class="text-sm text-green-600 dark:text-green-400">Compliant</div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-green-900 dark:text-green-100">PCI DSS</h4>
                            <p class="text-sm text-green-700 dark:text-green-300">Payment Card Industry Standards</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-green-600 dark:text-green-400">98%</div>
                        <div class="text-sm text-green-600 dark:text-green-400">Compliant</div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100">GDPR</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">Data Protection Regulation</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">92%</div>
                        <div class="text-sm text-yellow-600 dark:text-yellow-400">In Progress</div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-red-900 dark:text-red-100">SOC 2</h4>
                            <p class="text-sm text-red-700 dark:text-red-300">Service Organization Control</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-red-600 dark:text-red-400">75%</div>
                        <div class="text-sm text-red-600 dark:text-red-400">Non-Compliant</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Timeline -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Compliance Timeline</h3>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                <p class="text-gray-500 dark:text-gray-400">Chart.js Integration Required</p>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p>Compliance score trends over the last 12 months</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Risk Assessment</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Data Security</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">85%</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Access Control</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">92%</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Incident Response</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">78%</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Business Continuity</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">65%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Compliance Activities -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Compliance Activities</h3>
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
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Security Policy Updated</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Updated access control policies to meet ISO 27001 requirements</p>
                        <div class="mt-2 text-xs text-blue-600 dark:text-blue-400">2 hours ago</div>
                    </div>
                </div>

                <div class="flex items-start space-x-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-green-900 dark:text-green-100">PCI DSS Audit Completed</h4>
                        <p class="text-sm text-green-700 dark:text-green-300 mt-1">Annual PCI DSS compliance audit passed successfully</p>
                        <div class="mt-2 text-xs text-green-600 dark:text-green-400">1 day ago</div>
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
                        <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100">GDPR Assessment</h4>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">GDPR compliance assessment initiated - 3 items require attention</p>
                        <div class="mt-2 text-xs text-yellow-600 dark:text-yellow-400">3 days ago</div>
                    </div>
                </div>

                <div class="flex items-start space-x-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-red-900 dark:text-red-100">SOC 2 Gap Analysis</h4>
                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">SOC 2 Type II gap analysis revealed 5 critical compliance gaps</p>
                        <div class="mt-2 text-xs text-red-600 dark:text-red-400">1 week ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection













