@extends('layouts.admin')

@section('title', 'Create Regulatory Report')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Regulatory Report</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Generate a new regulatory compliance report</p>
            </div>
            <a href="{{ route('admin.regulatory-reports.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                Back to Reports
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Report Information</h3>
            </div>
            <div class="p-6">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Title</label>
                            <input type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter report title">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Type</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option>AML Report</option>
                                <option>KYC Compliance</option>
                                <option>Transaction Monitoring</option>
                                <option>Risk Assessment</option>
                                <option>Audit Report</option>
                                <option>Regulatory Filing</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reporting Period</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option>Daily</option>
                                <option>Weekly</option>
                                <option>Monthly</option>
                                <option>Quarterly</option>
                                <option>Annually</option>
                                <option>Custom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority Level</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option>Low</option>
                                <option>Medium</option>
                                <option>High</option>
                                <option>Critical</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                            <input type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                            <input type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Regulatory Authority</label>
                        <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option>Central Bank</option>
                            <option>Financial Services Authority</option>
                            <option>Securities Commission</option>
                            <option>Anti-Money Laundering Authority</option>
                            <option>Tax Authority</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Description</label>
                        <textarea rows="4" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Describe the purpose and scope of this report"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Sources</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Transaction Database</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">User Database</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">KYC Records</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Fraud Detection System</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Audit Logs</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Compliance Requirements</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">AML/CFT Compliance</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">KYC Requirements</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Transaction Monitoring</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Risk Assessment</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Data Protection</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Notes</label>
                        <textarea rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Any additional information or special requirements"></textarea>
                    </div>

                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Auto-generate report</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send to regulatory authority</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Require approval</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Generate Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection




