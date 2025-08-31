@extends('layouts.admin')

@section('title', 'System Settings - General')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Navigation Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-8 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.system.settings') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Overview
            </a>
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

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">General Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Configure general system settings and preferences</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">General Configuration</h3>
            </div>
            <div class="p-6">
                <form id="generalSettingsForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Name</label>
                            <input type="text" value="P-Finance" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter application name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Version</label>
                            <input type="text" value="1.0.0" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter version number">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Language</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="en" selected>English</option>
                                <option value="ar">Arabic</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="es">Spanish</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Timezone</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="UTC" selected>UTC</option>
                                <option value="America/New_York">Eastern Time</option>
                                <option value="America/Chicago">Central Time</option>
                                <option value="America/Denver">Mountain Time</option>
                                <option value="America/Los_Angeles">Pacific Time</option>
                                <option value="Europe/London">London</option>
                                <option value="Europe/Paris">Paris</option>
                                <option value="Asia/Dubai">Dubai</option>
                                <option value="Asia/Tokyo">Tokyo</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Currency</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="USD" selected>USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - British Pound</option>
                                <option value="AED">AED - UAE Dirham</option>
                                <option value="CAD">CAD - Canadian Dollar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Format</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="Y-m-d" selected>YYYY-MM-DD</option>
                                <option value="m/d/Y">MM/DD/YYYY</option>
                                <option value="d/m/Y">DD/MM/YYYY</option>
                                <option value="M d, Y">Jan 01, 2024</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Format</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="H:i:s" selected>24-hour (14:30:00)</option>
                                <option value="h:i:s A">12-hour (2:30:00 PM)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Decimal Places</label>
                            <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="0">0</option>
                                <option value="2" selected>2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Information</label>
                        <textarea rows="4" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter company description and information">P-Finance is a leading financial technology company providing innovative payment solutions and financial management tools.</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Email</label>
                            <input type="email" value="support@p-finance.com" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter contact email">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Support Phone</label>
                            <input type="tel" value="+1-800-123-4567" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter support phone">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website URL</label>
                        <input type="url" value="https://p-finance.com" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter website URL">
                    </div>

                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable maintenance mode</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable debug mode</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable user registration</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Reset to Defaults</button>
                        <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cool Alert Component -->
<div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Form submission handler
document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
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
    fetch('{{ route("admin.system.settings.update-general") }}', {
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
        document.getElementById('generalSettingsForm').reset();
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

