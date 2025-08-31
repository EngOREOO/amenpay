@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div x-data="dashboardData()" class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white shadow-xl animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <span x-show="language === 'en'">Welcome back, {{ auth()->user()->name ?? 'Admin' }}! 👋</span>
                    <span x-show="language === 'ar'">مرحباً بعودتك، {{ auth()->user()->name ?? 'المدير' }}! 👋</span>
                </h1>
                <p class="text-blue-100 text-lg">
                    <span x-show="language === 'en'">Here's what's happening with your Amen Pay platform today.</span>
                    <span x-show="language === 'ar'">إليك ما يحدث في منصة أمين باي اليوم.</span>
                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        <span x-show="language === 'en'">Total Users</span>
                        <span x-show="language === 'ar'">إجمالي المستخدمين</span>
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_users'] ?? 0) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +12.5%
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        <span x-show="language === 'en'">Total Transactions</span>
                        <span x-show="language === 'ar'">إجمالي المعاملات</span>
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_transactions'] ?? 0) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +8.2%
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        <span x-show="language === 'en'">Total Revenue</span>
                        <span x-show="language === 'ar'">إجمالي الإيرادات</span>
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">SAR {{ number_format($stats['total_revenue'] ?? 0) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +15.3%
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        <span x-show="language === 'en'">Active Users</span>
                        <span x-show="language === 'ar'">المستخدمون النشطون</span>
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['active_users'] ?? 0) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +5.7%
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-slide-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <span x-show="language === 'en'">Revenue Overview</span>
                    <span x-show="language === 'ar'">نظرة عامة على الإيرادات</span>
                </h3>
                <div class="flex space-x-2">
                    <button @click="chartPeriod = '7d'" :class="chartPeriod === '7d' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors duration-200">7D</button>
                    <button @click="chartPeriod = '30d'" :class="chartPeriod === '30d' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors duration-200">30D</button>
                    <button @click="chartPeriod = '90d'" :class="chartPeriod === '90d' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'" class="px-3 py-1 rounded-lg text-sm font-medium transition-colors duration-200">90D</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-slide-up" style="animation-delay: 0.5s;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <span x-show="language === 'en'">User Growth</span>
                    <span x-show="language === 'ar'">نمو المستخدمين</span>
                </h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        <span x-show="language === 'en'">New Users</span>
                        <span x-show="language === 'ar'">مستخدمون جدد</span>
                    </span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-slide-up" style="animation-delay: 0.6s;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <span x-show="language === 'en'">Recent Transactions</span>
                    <span x-show="language === 'ar'">المعاملات الأخيرة</span>
                </h3>
                <a href="{{ route('admin.transactions.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                    <span x-show="language === 'en'">View All</span>
                    <span x-show="language === 'ar'">عرض الكل</span>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentTransactions ?? [] as $transaction)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $transaction->user->name ?? 'Unknown User' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 dark:text-white">SAR {{ number_format($transaction->amount, 2) }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2">
                        <span x-show="language === 'en'">No recent transactions</span>
                        <span x-show="language === 'ar'">لا توجد معاملات حديثة</span>
                    </p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-slide-up" style="animation-delay: 0.7s;">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                <span x-show="language === 'en'">Quick Actions</span>
                <span x-show="language === 'ar'">إجراءات سريعة</span>
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span x-show="language === 'en'">Add New User</span>
                    <span x-show="language === 'ar'">إضافة مستخدم جديد</span>
                </a>
                <a href="{{ route('admin.transactions.create') }}" class="flex items-center p-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span x-show="language === 'en'">Create Transaction</span>
                    <span x-show="language === 'ar'">إنشاء معاملة</span>
                </a>
                <a href="{{ route('admin.kyc.index') }}" class="flex items-center p-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span x-show="language === 'en'">Review KYC</span>
                    <span x-show="language === 'ar'">مراجعة التحقق من الهوية</span>
                </a>
                <a href="{{ route('admin.reports.financial') }}" class="flex items-center p-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span x-show="language === 'en'">Generate Report</span>
                    <span x-show="language === 'ar'">إنشاء تقرير</span>
                </a>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-slide-up" style="animation-delay: 0.8s;">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            <span x-show="language === 'en'">System Status</span>
            <span x-show="language === 'ar'">حالة النظام</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <p class="font-medium text-green-800 dark:text-green-200">
                        <span x-show="language === 'en'">API Status</span>
                        <span x-show="language === 'ar'">حالة API</span>
                    </p>
                    <p class="text-sm text-green-600 dark:text-green-400">
                        <span x-show="language === 'en'">Operational</span>
                        <span x-show="language === 'ar'">يعمل</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <p class="font-medium text-blue-800 dark:text-blue-200">
                        <span x-show="language === 'en'">Database</span>
                        <span x-show="language === 'ar'">قاعدة البيانات</span>
                    </p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                        <span x-show="language === 'en'">Healthy</span>
                        <span x-show="language === 'ar'">صحي</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                <div class="w-3 h-3 bg-purple-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <p class="font-medium text-purple-800 dark:text-purple-200">
                        <span x-show="language === 'en'">Queue System</span>
                        <span x-show="language === 'ar'">نظام الطوابير</span>
                    </p>
                    <p class="text-sm text-purple-600 dark:text-purple-400">
                        <span x-show="language === 'en'">Active</span>
                        <span x-show="language === 'ar'">نشط</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl">
                <div class="w-3 h-3 bg-orange-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <p class="font-medium text-orange-800 dark:text-orange-200">
                        <span x-show="language === 'en'">Payment Gateway</span>
                        <span x-show="language === 'ar'">بوابة الدفع</span>
                    </p>
                    <p class="text-sm text-orange-600 dark:text-orange-400">
                        <span x-show="language === 'en'">Connected</span>
                        <span x-show="language === 'ar'">متصل</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function dashboardData() {
    return {
        chartPeriod: '30d',
        language: localStorage.getItem('language') || 'en',
        init() {
            this.initCharts();
        },
        initCharts() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Revenue',
                            data: [12000, 19000, 15000, 25000, 22000, 30000],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // User Growth Chart
            const userGrowthCtx = document.getElementById('userGrowthChart');
            if (userGrowthCtx) {
                new Chart(userGrowthCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'New Users',
                            data: [65, 78, 90, 81, 56, 55],
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        }
    }
}
</script>
@endpush
@endsection
