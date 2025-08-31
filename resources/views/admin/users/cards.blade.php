@extends('layouts.admin')

@section('title', 'User Cards')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Cards</h1>
                <p class="text-gray-600 dark:text-gray-400">Card information for {{ $user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.show', $user->id) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    Back to User
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    All Users
                </a>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ $user->phone }}</p>
            </div>
        </div>
    </div>

    <!-- Cards List -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Card Information</h3>
        </div>
        
        @if($cards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($cards as $card)
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-semibold">{{ $card->card_type ?? 'Credit Card' }}</h4>
                                <p class="text-blue-100 text-sm">{{ $card->bank_name ?? 'Bank' }}</p>
                            </div>
                            <div class="w-12 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="text-2xl font-mono mb-2">
                                **** **** **** {{ $card->last_four_digits ?? '1234' }}
                            </div>
                            <div class="flex justify-between text-sm text-blue-100">
                                <span>Expires: {{ $card->expiry_date ?? '12/25' }}</span>
                                <span>CVV: ***</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-blue-100">
                                <div>Card Holder</div>
                                <div class="font-semibold">{{ $user->name }}</div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $card->status === 'active' ? 'bg-green-400 text-green-900' : 
                                       ($card->status === 'suspended' ? 'bg-red-400 text-red-900' : 
                                        'bg-yellow-400 text-yellow-900') }}">
                                    {{ ucfirst($card->status ?? 'active') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No cards found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This user hasn't added any cards yet.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Card Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cards</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $cards->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Cards</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $cards->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Suspended</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $cards->where('status', 'suspended')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
