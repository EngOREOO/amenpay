@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
                <p class="text-gray-600 dark:text-gray-400">Configure system-wide settings and preferences</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <a href="{{ route('admin.system.general') }}" class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    General
                </a>
                <a href="{{ route('admin.system.security') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Security
                </a>
                <a href="{{ route('admin.system.notifications') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Notifications
                </a>
                <a href="{{ route('admin.system.integrations') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Integration
                </a>
            </nav>
        </div>

        <div class="p-6">
            <form id="overviewSettingsForm">
                @csrf
                <!-- General Settings -->
                <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">General Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application Name
                            </label>
                            <input type="text" 
                                   id="app_name" 
                                   name="app_name" 
                                   value="P-Finance"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                        </div>

                        <div>
                            <label for="app_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application URL
                            </label>
                            <input type="url" 
                                   id="app_url" 
                                   name="app_url" 
                                   value="https://p-finance.com"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Timezone
                            </label>
                            <select id="timezone" 
                                    name="timezone"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="Asia/Riyadh" selected>Asia/Riyadh (GMT+3)</option>
                                <option value="UTC">UTC (GMT+0)</option>
                                <option value="America/New_York">America/New_York (GMT-5)</option>
                                <option value="Europe/London">Europe/London (GMT+0)</option>
                            </select>
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Default Currency
                            </label>
                            <select id="currency" 
                                    name="currency"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="SAR" selected>Saudi Riyal (SAR)</option>
                                <option value="USD">US Dollar (USD)</option>
                                <option value="EUR">Euro (EUR)</option>
                                <option value="GBP">British Pound (GBP)</option>
                            </select>
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Default Language
                            </label>
                            <select id="language" 
                                    name="language"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="en" selected>English</option>
                                <option value="ar">العربية</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date Format
                            </label>
                            <select id="date_format" 
                                    name="date_format"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="Y-m-d" selected>YYYY-MM-DD</option>
                                <option value="d/m/Y">DD/MM/YYYY</option>
                                <option value="m/d/Y">MM/DD/YYYY</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">System Configuration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="maintenance_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Maintenance Mode
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="maintenance_mode" 
                                       name="maintenance_mode" 
                                       value="1"
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="maintenance_mode" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Enable maintenance mode
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Temporarily disable access for maintenance</p>
                        </div>

                        <div>
                            <label for="debug_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Debug Mode
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="debug_mode" 
                                       name="debug_mode" 
                                       value="1"
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="debug_mode" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Enable debug mode
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Show detailed error information (development only)</p>
                        </div>

                        <div>
                            <label for="auto_backup" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Auto Backup
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="auto_backup" 
                                       name="auto_backup" 
                                       value="1"
                                       checked
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="auto_backup" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Enable automatic backups
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Automatically backup database and files</p>
                        </div>

                        <div>
                            <label for="email_notifications" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Notifications
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="email_notifications" 
                                       name="email_notifications" 
                                       value="1"
                                       checked
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="email_notifications" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Enable email notifications
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Send system notifications via email</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Performance Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="cache_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cache Driver
                            </label>
                            <select id="cache_driver" 
                                    name="cache_driver"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="redis" selected>Redis</option>
                                <option value="memcached">Memcached</option>
                                <option value="file">File</option>
                                <option value="database">Database</option>
                            </select>
                        </div>

                        <div>
                            <label for="session_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Session Driver
                            </label>
                            <select id="session_driver" 
                                    name="session_driver"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="redis" selected>Redis</option>
                                <option value="database">Database</option>
                                <option value="file">File</option>
                            </select>
                        </div>

                        <div>
                            <label for="queue_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Queue Driver
                            </label>
                            <select id="queue_driver" 
                                    name="queue_driver"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="redis" selected>Redis</option>
                                <option value="database">Database</option>
                                <option value="sync">Synchronous</option>
                            </select>
                        </div>

                        <div>
                            <label for="log_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Log Level
                            </label>
                            <select id="log_level" 
                                    name="log_level"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="error" selected>Error</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                                <option value="debug">Debug</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">API Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="api_rate_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                API Rate Limit
                            </label>
                            <input type="number" 
                                   id="api_rate_limit" 
                                   name="api_rate_limit" 
                                   value="1000"
                                   min="100"
                                   max="10000"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Requests per minute per IP</p>
                        </div>

                        <div>
                            <label for="api_timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                API Timeout
                            </label>
                            <input type="number" 
                                   id="api_timeout" 
                                   name="api_timeout" 
                                   value="30"
                                   min="10"
                                   max="300"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Request timeout in seconds</p>
                        </div>

                        <div>
                            <label for="api_versioning" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                API Versioning
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="api_versioning" 
                                       name="api_versioning" 
                                       value="1"
                                       checked
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="api_versioning" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Enable API versioning
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Support multiple API versions</p>
                        </div>

                        <div>
                            <label for="api_documentation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                API Documentation
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="api_documentation" 
                                       name="api_documentation" 
                                       value="1"
                                       checked
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="api_documentation" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Enable API documentation
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Show API documentation at /api/docs</p>
                        </div>
                    </div>
                </div>
                
                <!-- Save Button -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="resetForm()" class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                            Reset to Defaults
                        </button>
                        <button type="submit" class="px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cool Alert Component -->
<div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Form submission handler
document.getElementById('overviewSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Saving...</span>
    `;
    submitBtn.disabled = true;
    
    // Submit form
    fetch('{{ route("admin.system.settings.update-overview") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('error', data.message || 'An error occurred while saving settings.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while saving settings.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Reset form function
function resetForm() {
    if (confirm('Are you sure you want to reset all settings to defaults?')) {
        document.getElementById('overviewSettingsForm').reset();
        showAlert('info', 'Form has been reset to default values.');
    }
}

// Cool alert function
function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    const alertId = 'alert-' + Date.now();
    
    const alertElement = document.createElement('div');
    alertElement.id = alertId;
    alertElement.className = `
        transform transition-all duration-300 ease-out
        ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}
        text-white px-6 py-4 rounded-xl shadow-lg
        flex items-center space-x-3
        translate-x-full opacity-0
    `;
    
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
    const iconClass = type === 'success' ? 'text-green-100' : type === 'error' ? 'text-red-100' : 'text-blue-100';
    
    alertElement.innerHTML = `
        <span class="text-2xl font-bold ${iconClass}">${icon}</span>
        <span class="font-medium">${message}</span>
        <button onclick="removeAlert('${alertId}')" class="ml-auto text-white hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    alertContainer.appendChild(alertElement);
    
    // Animate in
    setTimeout(() => {
        alertElement.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        removeAlert(alertId);
    }, 5000);
}

// Remove alert function
function removeAlert(alertId) {
    const alertElement = document.getElementById(alertId);
    if (alertElement) {
        alertElement.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            alertElement.remove();
        }, 300);
    }
}
</script>
@endsection
