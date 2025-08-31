<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Amen Pay</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-gradient-to-r from-emerald-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-xl font-semibold text-gray-900">Admin Profile</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-700">
                        Welcome, <span class="font-medium">{{ $admin->name }}</span>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Profile Information</h3>
                
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <input type="text" id="role" value="{{ ucfirst(str_replace('_', ' ', $admin->role)) }}" disabled
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="last_login" class="block text-sm font-medium text-gray-700">Last Login</label>
                            <input type="text" id="last_login" value="{{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}" disabled
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Change Password</h3>
                
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" id="current_password"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="new_password" id="new_password"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
