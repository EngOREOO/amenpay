<!DOCTYPE html>
<html lang="{{ $currentLocale ?? 'en' }}" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true', 
          language: localStorage.getItem('language') || '{{ $currentLocale ?? 'en' }}',
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          sidebarOpen: false
      }" 
      :class="{ 'dark': darkMode, 'rtl': language === 'ar' }" 
      :dir="language === 'ar' ? 'rtl' : 'ltr'"
      class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Amen Pay</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.294.0/lucide.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* CSS Variables for theming - Updated to match logo colors */
        :root {
            --primary-50: #e6f7f7;
            --primary-100: #b3e6e6;
            --primary-200: #80d4d4;
            --primary-300: #4dc2c2;
            --primary-400: #1ab0b0;
            --primary-500: #009999;
            --primary-600: #008080;
            --primary-700: #006666;
            --primary-800: #004d4d;
            --primary-900: #003333;
            
            --accent-50: #f0f9ff;
            --accent-100: #e0f2fe;
            --accent-200: #bae6fd;
            --accent-300: #7dd3fc;
            --accent-400: #38bdf8;
            --accent-500: #0ea5e9;
            --accent-600: #0284c7;
            --accent-700: #0369a1;
            --accent-800: #075985;
            --accent-900: #0c4a6e;
            
            --success-50: #ecfdf5;
            --success-100: #d1fae5;
            --success-200: #a7f3d0;
            --success-300: #6ee7b7;
            --success-400: #34d399;
            --success-500: #10b981;
            --success-600: #059669;
            --success-700: #047857;
            --success-800: #065f46;
            --success-900: #064e3b;
            
            --warning-50: #fffbeb;
            --warning-100: #fef3c7;
            --warning-200: #fde68a;
            --warning-300: #fcd34d;
            --warning-400: #fbbf24;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --warning-700: #b45309;
            --warning-800: #92400e;
            --warning-900: #78350f;
            
            --danger-50: #fef2f2;
            --danger-100: #fee2e2;
            --danger-200: #fecaca;
            --danger-300: #fca5a5;
            --danger-400: #f87171;
            --danger-500: #ef4444;
            --danger-600: #dc2626;
            --danger-700: #b91c1c;
            --danger-800: #991b1b;
            --danger-900: #7f1d1d;
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 84px;
            --header-height: 80px;
            --border-radius: 24px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        }
        
        /* Dark mode variables - Updated for teal theme */
        .dark {
            --primary-50: #003333;
            --primary-100: #004d4d;
            --primary-200: #006666;
            --primary-300: #008080;
            --primary-400: #009999;
            --primary-500: #1ab0b0;
            --primary-600: #4dc2c2;
            --primary-700: #80d4d4;
            --primary-800: #b3e6e6;
            --primary-900: #e6f7f7;
        }
        
        /* RTL Support - Fixed layout issues */
        .rtl .sidebar-rtl { 
            right: 0; 
            left: auto; 
        }
        .rtl .sidebar-rtl .sidebar-content { 
            padding-right: 0; 
            padding-left: 1rem; 
        }
        .rtl .main-content-rtl { 
            margin-right: var(--sidebar-width); 
            margin-left: 0; 
        }
        .rtl .main-content-rtl.collapsed { 
            margin-right: var(--sidebar-collapsed-width); 
        }
        .rtl .top-nav-rtl { 
            margin-right: var(--sidebar-width); 
            margin-left: 0; 
        }
        .rtl .top-nav-rtl.collapsed { 
            margin-right: var(--sidebar-collapsed-width); 
        }
        
        /* RTL Top Navigation Icons - Fixed positioning */
        .rtl .top-nav .search-container {
            order: 2;
        }
        
        .rtl .top-nav .right-side {
            order: 1;
            margin-left: 0;
            margin-right: auto;
        }
        
        .rtl .top-nav .mobile-menu-btn {
            order: 3;
        }
        
        /* RTL Top Navigation Icons - Fixed positioning */
        .rtl .top-nav .search-container {
            order: 2;
        }
        
        .rtl .top-nav .right-side {
            order: 1;
            margin-left: 0;
            margin-right: auto;
        }
        
        .rtl .top-nav .mobile-menu-btn {
            order: 3;
        }
        
        /* Language-specific fonts */
        .font-arabic { font-family: 'Cairo', sans-serif; }
        .font-english { font-family: 'Inter', sans-serif; }
        
        /* Smooth transitions */
        .transition-all { transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1); }
        .transition-transform { transition: transform 0.2s cubic-bezier(0.22, 1, 0.36, 1); }
        .transition-width { transition: width 0.2s cubic-bezier(0.22, 1, 0.36, 1); }
        
        /* Sidebar animations */
        .sidebar {
            width: var(--sidebar-width);
            transition: width 0.24s cubic-bezier(0.22, 1, 0.36, 1);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            transform: translateX(-10px);
        }
        
        .sidebar.collapsed .sidebar-icon {
            transform: scale(1.1);
        }
        
        /* Main content adjustments */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.24s cubic-bezier(0.22, 1, 0.36, 1);
        }
        
        .main-content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .rtl .main-content {
            margin-left: 0;
            margin-right: var(--sidebar-width);
        }
        
        .rtl .main-content.collapsed {
            margin-right: var(--sidebar-collapsed-width);
        }
        
        /* Top navigation adjustments */
        .top-nav {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.24s cubic-bezier(0.22, 1, 0.36, 1);
        }
        
        .top-nav.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .rtl .top-nav {
            margin-left: 0;
            margin-right: var(--sidebar-width);
        }
        
        .rtl .top-nav.collapsed {
            margin-right: var(--sidebar-collapsed-width);
        }
        
        /* Micro-interactions */
        .hover-lift {
            transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .btn-hover {
            transition: all 0.15s cubic-bezier(0.22, 1, 0.36, 1);
        }
        
        .btn-hover:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-hover:active {
            transform: scale(0.98);
        }
        
        /* Glass morphism effects */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .dark .glass {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
            }
            
            .rtl .sidebar {
                transform: translateX(100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content,
            .top-nav {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        
        /* Focus states for accessibility */
        .focus-ring:focus {
            outline: 2px solid var(--primary-500);
            outline-offset: 2px;
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .transition-all,
            .transition-transform,
            .transition-width,
            .sidebar,
            .main-content,
            .top-nav {
                transition: none;
            }
        }
        
        /* Logo styling */
        .logo-container {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-800) 100%);
            border: 2px solid var(--primary-400);
            box-shadow: 0 4px 20px rgba(0, 153, 153, 0.3);
        }
        
            .logo-text {
            background: linear-gradient(135deg, var(--primary-400) 0%, var(--accent-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Logo styling */
        .logo-container {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-800) 100%);
            border: 2px solid var(--primary-400);
            box-shadow: 0 4px 20px rgba(0, 153, 153, 0.3);
        }
        
        .logo-text {
            background: linear-gradient(135deg, var(--primary-400) 0%, var(--accent-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 h-full" 
      :class="language === 'ar' ? 'font-arabic' : 'font-english'">
    
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside class="sidebar fixed inset-y-0 left-0 z-50 bg-white dark:bg-gray-800 shadow-2xl transition-all duration-300 sidebar-rtl"
               :class="{ 'collapsed': sidebarCollapsed, 'open': sidebarOpen }"
               @keydown.escape="sidebarOpen = false">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="logo-container w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('logo.png') }}" alt="Amen Pay Logo" class="w-8 h-8 object-contain">
                    </div>
                    <div class="sidebar-text transition-all duration-300 min-w-0">
                        <h1 class="text-xl font-bold logo-text truncate">
                            <span x-show="language === 'en'">Amen Pay</span>
                            <span x-show="language === 'ar'">أمين باي</span>
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            <span x-show="language === 'en'">Admin Panel</span>
                            <span x-show="language === 'ar'">لوحة الإدارة</span>
                        </p>
                    </div>
                </div>
                
                <!-- Collapse Toggle Button -->
                <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                        class="hidden lg:flex p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus-ring"
                        :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                    <svg x-show="!sidebarCollapsed" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                    <svg x-show="sidebarCollapsed" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </button>
                
                <!-- Mobile Close Button -->
                <button @click="sidebarOpen = false" 
                        class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Language Switcher -->
            <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 sidebar-text transition-all duration-300">
                        <span x-show="language === 'en'">Language</span>
                        <span x-show="language === 'ar'">اللغة</span>
                    </span>
                    <div class="flex bg-gray-100 dark:bg-gray-700 rounded-xl p-1">
                        <button @click="language = 'en'; localStorage.setItem('language', 'en'); window.location.href = '{{ route('language.switch', 'en') }}'" 
                                :class="language === 'en' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400'"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200 focus-ring">
                            EN
                        </button>
                        <button @click="language = 'ar'; localStorage.setItem('language', 'ar'); window.location.href = '{{ route('language.switch', 'ar') }}'" 
                                :class="language === 'ar' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400'"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200 focus-ring">
                            عربي
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="px-4 py-6 space-y-2 sidebar-content overflow-y-auto h-full">
                @include('admin.partials.navigation')
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navigation -->
            <header class="top-nav sticky top-0 z-30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700 top-nav-rtl transition-all duration-300"
                    :class="{ 'collapsed': sidebarCollapsed }">
                <div class="flex items-center justify-between h-20 px-6">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = true" 
                            class="mobile-menu-btn lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus-ring">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Search Bar -->
                    <div class="search-container flex-1 max-w-lg mx-4 lg:mx-8">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   :placeholder="language === 'en' ? '{{ $translations['common']['search'] ?? 'Search...' }}' : '{{ $translations['common']['search'] ?? 'بحث...' }}'"
                                   class="block w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 transition-all duration-200 focus-ring">
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="right-side flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                                class="p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus-ring">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus-ring">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 004 6v10a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                                </svg>
                                <span class="absolute top-2 right-2 block h-2.5 w-2.5 rounded-full bg-danger-500 animate-pulse"></span>
                            </button>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-3 text-sm rounded-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 focus-ring">
                                <img class="h-10 w-10 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600" 
                                     src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=009999&color=fff&size=128" alt="">
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                                </div>
                                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" 
                                     :class="{ 'rotate-180': open }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl py-2 z-50 border border-gray-200 dark:border-gray-700">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <span x-show="language === 'en'">{{ $translations['common']['profile'] ?? 'Profile' }}</span>
                                    <span x-show="language === 'ar'">{{ $translations['common']['profile'] ?? 'الملف الشخصي' }}</span>
                                </a>
                                <a href="#" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <span x-show="language === 'en'">{{ $translations['common']['settings'] ?? 'Settings' }}</span>
                                    <span x-show="language === 'ar'">{{ $translations['common']['settings'] ?? 'الإعدادات' }}</span>
                                </a>
                                <hr class="my-2 border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <span x-show="language === 'en'">{{ $translations['common']['logout'] ?? 'Logout' }}</span>
                                        <span x-show="language === 'ar'">{{ $translations['common']['logout'] ?? 'تسجيل الخروج' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="main-content flex-1 transition-all duration-300 transition-all duration-300"
                  :class="{ 'collapsed': sidebarCollapsed }">
                <div class="py-8 px-6 lg:px-8">
                    @if(session('success'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform translate-y-2"
                             class="mb-8 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 text-success-700 dark:text-success-400 px-6 py-4 rounded-2xl flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                            <button @click="show = false" class="text-success-500 hover:text-success-700 dark:text-success-400 dark:hover:text-success-300 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform translate-y-2"
                             class="mb-8 bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800 text-danger-700 dark:text-danger-400 px-6 py-4 rounded-2xl flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('error') }}
                            </div>
                            <button @click="show = false" class="text-danger-500 hover:text-danger-700 dark:text-danger-400 dark:hover:text-danger-300 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- Page Transition Container -->
                    <div x-data="{ 
                        pageEnter: true,
                        pageExit: false
                    }"
                    x-init="
                        pageEnter = true;
                        setTimeout(() => pageEnter = false, 100);
                    "
                    x-show="!pageExit"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform translate-y-4"
                    class="page-content">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <script>
        // Initialize language from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const savedLanguage = localStorage.getItem('language');
            if (savedLanguage) {
                document.documentElement.lang = savedLanguage;
                if (savedLanguage === 'ar') {
                    document.documentElement.dir = 'rtl';
                }
            }
        });
        
        // Handle page transitions
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.includes('#') && !link.target && !e.ctrlKey && !e.metaKey) {
                const pageContent = document.querySelector('.page-content');
                if (pageContent && pageContent._x_dataStack) {
                    const alpineData = pageContent._x_dataStack[0];
                    if (alpineData && typeof alpineData.pageExit === 'function') {
                        alpineData.pageExit = true;
                    }
                }
            }
        });
    </script>
</body>
</html>
