@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New User</h1>
                <p class="text-gray-600 dark:text-gray-400">Add a new user to the system</p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                Back to Users
            </a>
        </div>
    </div>

    <!-- Create User Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg animate-slide-up">
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Full Name *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number *
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                           placeholder="+966500000000"
                           required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                           placeholder="user@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="national_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        National ID
                    </label>
                    <input type="text" 
                           id="national_id" 
                           name="national_id" 
                           value="{{ old('national_id') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                           placeholder="1234567890">
                    @error('national_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Language
                    </label>
                    <select id="language" 
                            name="language"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                        <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                        <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>العربية</option>
                    </select>
                    @error('language')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select id="status" 
                            name="status"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Verification Settings -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Verification Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_verified" 
                               name="is_verified" 
                               value="1"
                               {{ old('is_verified') ? 'checked' : '' }}
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="is_verified" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Mark as verified
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="email_verified" 
                               name="email_verified" 
                               value="1"
                               {{ old('email_verified') ? 'checked' : '' }}
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="email_verified" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Email verified
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 transform hover:scale-105">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
