@extends('layouts.admin')

@section('title', 'KYC Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">KYC Verification Details</h1>
                <p class="text-gray-600 dark:text-gray-400">Review user verification information</p>
            </div>
            <div class="flex space-x-3">
                @if($kyc->status === 'pending')
                    <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                            Approve KYC
                        </button>
                    </form>
                    <button onclick="openRejectModal()" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        Reject KYC
                    </button>
                @endif
                <a href="{{ route('admin.kyc.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Back to KYC
                </a>
            </div>
        </div>
    </div>

    <!-- KYC Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $kyc->user->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $kyc->user->phone ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $kyc->user->email ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">National ID</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $kyc->user->national_id ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Verification Details -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Verification Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Verification Type</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ ucfirst($kyc->verification_type ?? 'individual') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Verification Level</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ ucfirst($kyc->verification_level ?? 'basic') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'under_review' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            ];
                            $statusColor = $statusColors[$kyc->status ?? 'pending'] ?? $statusColors['pending'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                            {{ ucfirst(str_replace('_', ' ', $kyc->status ?? 'pending')) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Risk Assessment</label>
                        @php
                            $riskColors = [
                                'low' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'high' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            ];
                            $riskColor = $riskColors[$kyc->risk_assessment ?? 'low'] ?? $riskColors['low'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $riskColor }}">
                            {{ ucfirst($kyc->risk_assessment ?? 'low') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Submitted Documents</h3>
                <div class="space-y-4">
                    @if($kyc->identity_documents)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Identity Documents</label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700 dark:text-gray-300">{{ $kyc->identity_documents }}</pre>
                            </div>
                        </div>
                    @endif

                    @if($kyc->address_proof)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Address Proof</label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700 dark:text-gray-300">{{ $kyc->address_proof }}</pre>
                            </div>
                        </div>
                    @endif

                    @if($kyc->income_documents)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Income Documents</label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700 dark:text-gray-300">{{ $kyc->income_documents }}</pre>
                            </div>
                        </div>
                    @endif

                    @if($kyc->business_documents)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Business Documents</label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700 dark:text-gray-300">{{ $kyc->business_documents }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Compliance Information -->
            @if($kyc->compliance_flags || $kyc->aml_flags || $kyc->sanctions_matches)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Compliance Information</h3>
                <div class="space-y-4">
                    @if($kyc->compliance_flags)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Compliance Flags</label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700 dark:text-gray-300">{{ $kyc->compliance_flags }}</pre>
                            </div>
                        </div>
                    @endif

                    @if($kyc->aml_flags)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">AML Flags</label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700 dark:text-gray-300">{{ $kyc->aml_flags }}</pre>
                            </div>
                        </div>
                    @endif

                    @if($kyc->sanctions_matches)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Sanctions Matches</label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700 dark:text-gray-300">{{ $kyc->sanctions_matches }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Verification Timeline</h3>
                <div class="space-y-4">
                    @if($kyc->submitted_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Submitted</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($kyc->submitted_at)->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($kyc->reviewed_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-3 h-3 bg-yellow-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Under Review</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($kyc->reviewed_at)->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($kyc->approved_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Approved</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($kyc->approved_at)->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($kyc->expires_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-3 h-3 bg-gray-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Expires</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($kyc->expires_at)->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        View User Profile
                    </button>
                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        View Transactions
                    </button>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        Request Additional Info
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reject KYC Request</h3>
            <form action="{{ route('admin.kyc.reject', $kyc->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection *
                    </label>
                    <textarea id="rejection_reason" 
                              name="rejection_reason" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                              required></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200">
                        Reject KYC
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
