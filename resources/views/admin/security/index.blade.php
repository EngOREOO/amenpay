@extends('layouts.admin')

@section('title', 'Security Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Security Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400">Monitor and manage system security</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    View Security Alerts
                </button>
            </div>
        </div>
    </div>

    <!-- Security Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Security Score -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Security Score</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">92/100</p>
                    <p class="text-sm text-green-600 dark:text-green-400">Excellent</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Threats -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Threats</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">3</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Medium risk</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Failed Login Attempts -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Failed Logins</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">156</p>
                    <p class="text-sm text-red-600 dark:text-red-400">+23 today</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Security Incidents -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Security Incidents</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">2</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">This month</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Security Status -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Security Status</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Firewall</span>
                    </div>
                    <span class="text-sm text-green-600 dark:text-green-400">Active</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">SSL/TLS</span>
                    </div>
                    <span class="text-sm text-green-600 dark:text-green-400">Valid</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Two-Factor Auth</span>
                    </div>
                    <span class="text-sm text-yellow-600 dark:text-yellow-400">75% Enabled</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Backup Encryption</span>
                    </div>
                    <span class="text-sm text-green-600 dark:text-green-400">Active</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Vulnerability Scan</span>
                    </div>
                    <span class="text-sm text-red-600 dark:text-red-400">Overdue</span>
                </div>
            </div>
        </div>

        <!-- Recent Security Events -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Security Events</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Failed login attempt from suspicious IP</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">IP: 192.168.1.100 • 2 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Multiple failed authentication attempts</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">User: admin@example.com • 4 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">New admin user created</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">User: security@example.com • 1 day ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Security policy updated</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Policy: Password Complexity • 2 days ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Policies -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Security Policies</h3>
            <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                Add Policy
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Password Policy</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Minimum 8 characters, uppercase, lowercase, numbers</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: Dec 1, 2024</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Session Timeout</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Auto-logout after 30 minutes of inactivity</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: Nov 15, 2024</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">IP Whitelist</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        Partial
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Restrict admin access to specific IP addresses</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: Dec 10, 2024</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Two-Factor Auth</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Required for all admin accounts</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: Nov 20, 2024</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">File Upload Security</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Restrict file types and scan for malware</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: Dec 5, 2024</div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 dark:text-white">Audit Logging</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Log all security-related activities</p>
                <div class="text-xs text-gray-500 dark:text-gray-400">Last updated: Nov 25, 2024</div>
            </div>
        </div>
    </div>

    <!-- Security Recommendations -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Security Recommendations</h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <div class="flex-shrink-0 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 dark:text-white">Enable Two-Factor Authentication for All Users</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Currently only 75% of users have 2FA enabled. Consider making it mandatory for all accounts.</p>
                </div>
            </div>

            <div class="flex items-start space-x-3 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <div class="flex-shrink-0 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 dark:text-white">Run Vulnerability Scan</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">The last vulnerability scan was 45 days ago. Schedule a new scan to identify potential security issues.</p>
                </div>
            </div>

            <div class="flex items-start space-x-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 dark:text-white">Review Failed Login Attempts</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">There have been 156 failed login attempts today. Review and consider implementing additional security measures.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
