@extends('layouts.admin')

@section('title', 'KYC Verification')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">KYC Verification</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage user identity verification requests</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Pending:</span>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                        {{ $pendingCount ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Approved:</span>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                        {{ $approvedCount ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-wrap items-center space-x-4">
                <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="under_review">Under Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                
                <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Types</option>
                    <option value="individual">Individual</option>
                    <option value="business">Business</option>
                    <option value="corporate">Corporate</option>
                </select>
            </div>
            
            <div class="relative">
                <input type="text" 
                       placeholder="Search by name, phone, or ID..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white w-64">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- KYC Requests Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg animate-slide-up">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Risk Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($kycRequests ?? [] as $kyc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600" 
                                             src="https://ui-avatars.com/api/?name={{ $kyc->user->name ?? 'User' }}&background=3b82f6&color=fff" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $kyc->user->name ?? 'Unknown User' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $kyc->user->phone ?? 'No phone' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ ucfirst($kyc->verification_type ?? 'individual') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $kyc->submitted_at ? \Carbon\Carbon::parse($kyc->submitted_at)->diffForHumans() : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.kyc.show', $kyc->id) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">Review</a>
                                    @if($kyc->status === 'pending')
                                    <button class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Approve</button>
                                    <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Reject</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">No KYC requests found</p>
                                    <p class="text-gray-500 dark:text-gray-400">All verification requests have been processed</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($kycRequests) && $kycRequests->hasPages())
            <div class="mt-6">
                {{ $kycRequests->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
