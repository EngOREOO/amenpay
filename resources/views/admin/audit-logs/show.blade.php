@extends('layouts.admin')

@section('title', 'Audit Log Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Audit Log Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">View detailed audit log information</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                Back to Audit Logs
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Log Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Log Overview</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">#AUDIT-001</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Log ID</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">User Login</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Event Type</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">Medium</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Risk Level</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">2 min ago</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Timestamp</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Event Type</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">User Login</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Event Category</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Authentication</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Risk Level</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Medium</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Success</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">March 30, 2024 at 14:32:15</p>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User ID</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">12345</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Username</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">john.doe@email.com</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User Role</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Customer</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">IP Address</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">192.168.1.100</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User Agent</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">Mozilla/5.0 (Windows NT 10.0; Win64; x64)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Event Details</h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Event Description</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">User successfully logged into the system from a recognized device and IP address. The login attempt was authenticated using multi-factor authentication.</p>
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Event Data</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <pre class="text-sm text-gray-600 dark:text-gray-400 overflow-x-auto">{
  "event_type": "user_login",
  "user_id": 12345,
  "timestamp": "2024-03-30T14:32:15Z",
  "ip_address": "192.168.1.100",
  "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
  "session_id": "sess_abc123def456",
  "authentication_method": "password_mfa",
  "success": true,
  "risk_score": 25
}</pre>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Risk Assessment</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-green-600 dark:text-green-400">Low Risk</div>
                                <div class="text-sm text-green-600 dark:text-green-400">Recognized Device</div>
                            </div>
                            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">Medium Risk</div>
                                <div class="text-sm text-yellow-600 dark:text-yellow-400">New IP Address</div>
                            </div>
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-green-600 dark:text-green-400">Low Risk</div>
                                <div class="text-sm text-green-600 dark:text-green-400">MFA Enabled</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Events -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Related Events</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Previous Login</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">March 29, 2024 at 09:15:30</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Success</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Password Change</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">March 25, 2024 at 16:45:12</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Success</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Failed Login Attempt</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">March 24, 2024 at 22:30:45</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Failed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h3>
            </div>
            <div class="p-6">
                <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        Export Log
                    </button>
                    <button class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                        Mark as Reviewed
                    </button>
                    <button class="px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700">
                        Flag for Investigation
                    </button>
                    <button class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                        Add Note
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




