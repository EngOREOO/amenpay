@extends('layouts.admin')

@section('title', 'Regulatory Report Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Regulatory Report Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">View and manage regulatory compliance report</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.regulatory-reports.edit', $id) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                    Edit Report
                </a>
                <a href="{{ route('admin.regulatory-reports.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Report Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Report Overview</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">Q1 2024</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Reporting Period</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">AML Report</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Report Type</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">High</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Priority</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">Pending</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Status</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Report Title</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Q1 2024 Anti-Money Laundering Compliance Report</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Report Type</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">AML Report</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reporting Period</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Quarterly</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Priority Level</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">High</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created Date</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">March 31, 2024</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">April 15, 2024</p>
                    </div>
                </div>
            </div>

            <!-- Regulatory Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Regulatory Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Regulatory Authority</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Central Bank</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Compliance Framework</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">AML/CFT Guidelines 2024</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Submission Method</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Online Portal</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">John Smith - Compliance Officer</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reference Number</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">CB-AML-2024-Q1-001</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Report Content</h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Executive Summary</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">This report provides a comprehensive overview of our anti-money laundering and counter-terrorism financing compliance activities for Q1 2024. The organization has maintained strong compliance with all regulatory requirements and implemented enhanced monitoring systems.</p>
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Key Findings</h4>
                        <ul class="list-disc list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li>Total transactions monitored: 45,230</li>
                            <li>Suspicious activity reports filed: 12</li>
                            <li>KYC verifications completed: 1,247</li>
                            <li>Risk assessments conducted: 89</li>
                            <li>Compliance training sessions: 24</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Risk Assessment</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-green-600 dark:text-green-400">Low Risk</div>
                                <div class="text-sm text-green-600 dark:text-green-400">Customer Due Diligence</div>
                            </div>
                            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">Medium Risk</div>
                                <div class="text-sm text-yellow-600 dark:text-yellow-400">Transaction Monitoring</div>
                            </div>
                            <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-red-600 dark:text-red-400">High Risk</div>
                                <div class="text-sm text-red-600 dark:text-red-400">Geographic Risk</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance Metrics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Compliance Metrics</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">98.7%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">KYC Compliance</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">99.2%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Transaction Monitoring</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">95.8%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Risk Assessment</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">100%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Staff Training</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Items -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Action Items</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Enhanced Monitoring</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">Implement additional monitoring for high-risk transactions</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">In Progress</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Staff Training</h4>
                            <p class="text-sm text-green-700 dark:text-green-300 mt-1">Complete AML training for all staff members</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Completed</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Policy Update</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Review and update AML policies and procedures</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Pending</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Report Actions</h3>
            </div>
            <div class="p-6">
                <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                        Approve Report
                    </button>
                    <button class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        Submit to Authority
                    </button>
                    <button class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                        Export PDF
                    </button>
                    <button class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                        Share Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




