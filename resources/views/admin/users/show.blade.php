@extends('layouts.admin')

@section('title', __('userManagement.userDetails'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@__('userManagement.userDetails')</h1>
                <p class="text-gray-600 dark:text-gray-400">@__('userManagement.subtitle')</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user->id) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    @__('actions.edit') @__('common.user')
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    @__('actions.back') @__('actions.to') @__('nav.userManagement')
                </a>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Details -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@__('userManagement.personalInfo')</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.fullName')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.phoneNumber')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.emailAddress')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->email ?? __('userManagement.notProvided') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.nationalId')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->national_id ?? __('userManagement.notProvided') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.language')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">
                            {{ $user->language === 'ar' ? __('userManagement.arabic') : __('userManagement.english') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('tables.headers.status')</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                               ($user->status === 'suspended' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                            {{ __('status.' . ($user->status ?? 'active')) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@__('userManagement.accountStatus')</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.verificationStatus')</label>
                        <div class="flex items-center mt-1">
                            @if($user->is_verified)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    @__('status.verified')
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                    </svg>
                                    @__('status.unverified')
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.emailVerification')</label>
                        <div class="flex items-center mt-1">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    @__('status.verified')
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                    </svg>
                                    @__('status.unverified')
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.lastLogin')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : __('userManagement.never') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.memberSince')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">
                            {{ $user->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Wallet Information -->
            @if($user->wallet)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@__('userManagement.walletInfo')</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.walletNumber')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->wallet->wallet_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('tables.headers.balance')</label>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            @__('currency.sar') {{ number_format($user->wallet->balance, 2) }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('tables.headers.currency')</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->wallet->currency }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">@__('userManagement.walletStatus')</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $user->wallet->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ __('status.' . $user->wallet->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@__('common.quickActions')</h3>
                <div class="space-y-3">
                    <button onclick="verifyUser({{ $user->id }})" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        @__('actions.verify') @__('common.user')
                    </button>
                    <a href="{{ route('admin.users.transactions.view', $user->id) }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center block">
                        @__('actions.view') @__('nav.transactions')
                    </a>
                    <a href="{{ route('admin.users.cards.view', $user->id) }}" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center block">
                        @__('actions.view') @__('common.cards')
                    </a>
                    <button onclick="suspendUser({{ $user->id }})" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        @__('actions.suspend') @__('common.user')
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@__('userManagement.quickStats')</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">@__('userManagement.totalTransactions')</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $user->transactions()->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">@__('userManagement.totalCards')</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $user->cards()->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">@__('userManagement.activeSessions')</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $user->sessions()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function verifyUser(userId) {
    showConfirmModal(
        '@__("actions.verify") @__("common.user")',
        '@__("modals.areYouSure") @__("actions.verify") @__("common.user")؟',
        'verify',
        'bg-green-500 hover:bg-green-600',
        () => {
            fetch(`/admin/users/${userId}/verify`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showCoolAlert('@__("messages.userVerifiedSuccessfully")', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showCoolAlert('@__("messages.failedToVerifyUser")', 'error');
                }
            })
            .catch(error => {
                showCoolAlert('@__("messages.anErrorOccurred")', 'error');
            });
        }
    );
}

function suspendUser(userId) {
    showConfirmModal(
        '@__("actions.suspend") @__("common.user")',
        '@__("modals.areYouSure") @__("actions.suspend") @__("common.user")؟ @__("modals.thisActionCannotBeUndone")',
        'suspend',
        'bg-yellow-500 hover:bg-yellow-600',
        () => {
            fetch(`/admin/users/${userId}/suspend`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showCoolAlert('@__("messages.userSuspendedSuccessfully")', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showCoolAlert('@__("messages.failedToSuspendUser")', 'error');
                }
            })
            .catch(error => {
                showCoolAlert('@__("messages.anErrorOccurred")', 'error');
            });
        }
    );
}

function showConfirmModal(title, message, action, buttonClass, onConfirm) {
    // Remove existing modal if any
    const existingModal = document.getElementById('confirmModal');
    if (existingModal) {
        existingModal.remove();
    }

    const modal = document.createElement('div');
    modal.id = 'confirmModal';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full ${action === 'verify' ? 'bg-green-100 dark:bg-green-900' : 'bg-yellow-100 dark:bg-yellow-900'} flex items-center justify-center">
                        ${action === 'verify' ? 
                            '<svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
                            '<svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>'
                        }
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${title}</h3>
                </div>
                <button onclick="closeConfirmModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">${message}</p>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-3 p-6 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeConfirmModal()" class="flex-1 px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-all duration-200 font-medium">
                    @__('actions.cancel')
                </button>
                <button onclick="executeAction()" class="flex-1 px-4 py-2 text-white ${buttonClass} rounded-lg transition-all duration-200 font-medium transform hover:scale-105">
                    ${action === 'verify' ? '@__("actions.verify") @__("common.user")' : '@__("actions.suspend") @__("common.user")'}
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    
    // Store the callback function
    window.executeAction = onConfirm;
    
    // Animate in
    setTimeout(() => {
        const modalContent = modal.querySelector('.bg-white, .dark\\:bg-gray-800');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        const modalContent = modal.querySelector('.bg-white, .dark\\:bg-gray-800');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.remove();
        }, 200);
    }
}

function showCoolAlert(message, type) {
    // Remove existing alert if any
    const existingAlert = document.getElementById('coolAlert');
    if (existingAlert) {
        existingAlert.remove();
    }

    const alert = document.createElement('div');
    alert.id = 'coolAlert';
    alert.className = 'fixed inset-0 z-50 flex items-center justify-center p-4';
    alert.innerHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-sm w-full mx-4 transform transition-all duration-500 scale-50 opacity-0">
            <!-- Success Icon -->
            <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                <div class="w-12 h-12 rounded-full ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} flex items-center justify-center shadow-lg">
                    ${type === 'success' ? 
                        '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' :
                        '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>'
                    }
                </div>
            </div>
            
            <!-- Content -->
            <div class="pt-8 pb-6 px-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    ${type === 'success' ? '@__("modals.success")!' : '@__("modals.error")!'}
                </h3>
                <p class="text-gray-600 dark:text-gray-400">${message}</p>
            </div>
            
            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 rounded-b-2xl overflow-hidden">
                <div class="h-full ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} transition-all duration-3000 ease-linear" style="width: 100%"></div>
            </div>
        </div>
    `;

    document.body.appendChild(alert);
    
    // Animate in
    setTimeout(() => {
        const alertContent = alert.querySelector('.bg-white, .dark\\:bg-gray-800');
        alertContent.classList.remove('scale-50', 'opacity-0');
        alertContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Auto close after 3 seconds
    setTimeout(() => {
        const alertContent = alert.querySelector('.bg-white, .dark\\:bg-gray-800');
        alertContent.classList.add('scale-50', 'opacity-0');
        setTimeout(() => {
            alert.remove();
        }, 300);
    }, 3000);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('confirmModal');
    if (modal && event.target === modal) {
        closeConfirmModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeConfirmModal();
    }
});
</script>
@endpush
