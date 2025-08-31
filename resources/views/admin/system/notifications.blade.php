@extends('layouts.admin')

@section('title', 'Notification Settings')

@section('content')
<div class="space-y-6">
    <!-- Navigation Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
        <nav class="flex space-x-8 border-b border-gray-200 dark:border-gray-700 mb-6">
            <a href="{{ route('admin.system.settings') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Overview
            </a>
            <a href="{{ route('admin.system.general') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                General
            </a>
            <a href="{{ route('admin.system.security') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Security
            </a>
            <a href="{{ route('admin.system.notifications') }}" class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Notifications
            </a>
            <a href="{{ route('admin.system.integrations') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Integration
            </a>
        </nav>
    </div>

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notification Settings</h1>
                <p class="text-gray-600 dark:text-gray-400">Configure system notifications and alerts</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Notification Settings -->
    <form id="notificationsSettingsForm">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
            <div class="p-6 space-y-8">
            <!-- Email Notifications -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Email Notifications</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Transaction Alerts</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Receive email notifications for all transactions</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Security Alerts</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Get notified about suspicious activities</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">KYC Updates</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Notifications for KYC verification status changes</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">System Maintenance</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Receive updates about system maintenance</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Push Notifications -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Push Notifications</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Mobile Push</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Send push notifications to mobile devices</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Browser Notifications</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Show notifications in web browsers</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SMS Notifications -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">SMS Notifications</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Critical Alerts</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Send SMS for critical security alerts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">OTP Codes</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Send OTP codes via SMS</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notification Frequency -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Notification Frequency</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Frequency
                        </label>
                        <select id="email_frequency" 
                                name="email_frequency"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            <option value="immediate" selected>Immediate</option>
                            <option value="hourly">Hourly Digest</option>
                            <option value="daily">Daily Digest</option>
                            <option value="weekly">Weekly Digest</option>
                        </select>
                    </div>

                    <div>
                        <label for="push_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Push Notification Frequency
                        </label>
                        <select id="push_frequency" 
                                name="push_frequency"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            <option value="immediate" selected>Immediate</option>
                            <option value="batched">Batched (Every 15 min)</option>
                            <option value="hourly">Hourly</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Quiet Hours -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quiet Hours</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quiet_hours_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Time
                        </label>
                        <input type="time" 
                               id="quiet_hours_start" 
                               name="quiet_hours_start" 
                               value="22:00"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                    </div>

                    <div>
                        <label for="quiet_hours_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Time
                        </label>
                        <input type="time" 
                               id="quiet_hours_end" 
                               name="quiet_hours_end" 
                               value="08:00"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">During quiet hours, only critical notifications will be sent</p>
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
        </div>
    </form>
</div>

<!-- Cool Alert Component -->
<div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Form submission handler
document.getElementById('notificationsSettingsForm').addEventListener('submit', function(e) {
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
    fetch('{{ route("admin.system.settings.update-notifications") }}', {
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
        document.getElementById('notificationsSettingsForm').reset();
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
