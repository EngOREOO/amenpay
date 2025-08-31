@extends('layouts.admin')

@section('title', 'Integration Settings')

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
            <a href="{{ route('admin.system.notifications') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Notifications
            </a>
            <a href="{{ route('admin.system.integrations') }}" class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Integration
            </a>
        </nav>
    </div>

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Integration Settings</h1>
                <p class="text-gray-600 dark:text-gray-400">Configure third-party integrations and APIs</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Gateway Integrations -->
    <form id="integrationsSettingsForm">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
            <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Gateway Integrations</h3>
            <div class="space-y-4">
                <!-- Stripe Integration -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 14a6 6 0 1112 0H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Stripe</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Credit card and digital wallet payments</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Connected
                            </span>
                            <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                Disconnect
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Publishable Key</label>
                            <input type="text" value="pk_test_..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secret Key</label>
                            <input type="password" value="sk_test_..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" readonly>
                        </div>
                    </div>
                </div>

                <!-- PayPal Integration -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 14a6 6 0 1112 0H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">PayPal</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Digital payments and transfers</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                Not Connected
                            </span>
                            <button class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                                Connect
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bank Transfer Integration -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 14a6 6 0 1112 0H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Bank Transfer</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Direct bank account transfers</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Connected
                            </span>
                            <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                Disconnect
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SMS Gateway Integrations -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">SMS Gateway Integrations</h3>
            <div class="space-y-4">
                <!-- Twilio Integration -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 14a6 6 0 1112 0H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Twilio</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">SMS and voice messaging</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Connected
                            </span>
                            <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                Disconnect
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account SID</label>
                            <input type="text" value="AC..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Auth Token</label>
                            <input type="password" value="..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Service Integrations -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Email Service Integrations</h3>
            <div class="space-y-4">
                <!-- SendGrid Integration -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 14a6 6 0 1112 0H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">SendGrid</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Transactional email delivery</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Connected
                            </span>
                            <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                Disconnect
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API Key</label>
                            <input type="password" value="SG..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Email</label>
                            <input type="email" value="noreply@p-finance.com" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Integrations -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Analytics Integrations</h3>
            <div class="space-y-4">
                <!-- Google Analytics -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 14a6 6 0 1112 0H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Google Analytics</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Website and user analytics</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                Not Connected
                            </span>
                            <button class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                                Connect
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mixpanel Integration -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 14a6 6 0 1112 0H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Mixpanel</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">User behavior analytics</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                Not Connected
                            </span>
                            <button class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                                Connect
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Webhook Configuration -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Webhook Configuration</h3>
            <div class="space-y-4">
                <div>
                    <label for="webhook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Webhook URL
                    </label>
                    <input type="url" 
                           id="webhook_url" 
                           name="webhook_url" 
                           value="https://api.p-finance.com/webhooks"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">URL to receive real-time updates and notifications</p>
                </div>

                <div>
                    <label for="webhook_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Webhook Secret
                    </label>
                    <input type="password" 
                           id="webhook_secret" 
                           name="webhook_secret" 
                           value="whsec_..."
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Secret key to verify webhook authenticity</p>
                </div>

                <div class="flex items-center space-x-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="webhook_retries" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Enable webhook retries</span>
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
        </div>
    </form>
</div>

<!-- Cool Alert Component -->
<div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Form submission handler
document.getElementById('integrationsSettingsForm').addEventListener('submit', function(e) {
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
    fetch('{{ route("admin.system.settings.update-integrations") }}', {
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
        document.getElementById('integrationsSettingsForm').reset();
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
