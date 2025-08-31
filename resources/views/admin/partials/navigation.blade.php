<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-primary-500/10 hover:to-primary-600/10 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-primary-500/10 to-primary-600/10 text-primary-600 dark:text-primary-400 border-r-4 border-primary-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.dashboard') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['dashboard'] ?? 'Dashboard' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['dashboard'] ?? 'لوحة التحكم' }}</span>
    </span>
</a>

<!-- User Management -->
<a href="{{ route('admin.users.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-success-500/10 hover:to-success-600/10 hover:text-success-600 dark:hover:text-success-400 transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-success-500/10 to-success-600/10 text-success-600 dark:text-success-400 border-r-4 border-success-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.users.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['userManagement'] ?? 'User Management' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['userManagement'] ?? 'إدارة المستخدمين' }}</span>
    </span>
</a>

<!-- Transaction Management -->
<a href="{{ route('admin.transactions.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-purple-500/10 hover:to-purple-600/10 hover:text-purple-600 dark:hover:text-purple-400 transition-all duration-200 {{ request()->routeIs('admin.transactions.*') ? 'bg-gradient-to-r from-purple-500/10 to-purple-600/10 text-purple-600 dark:text-purple-400 border-r-4 border-purple-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.transactions.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['transactions'] ?? 'Transactions' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['transactions'] ?? 'المعاملات' }}</span>
    </span>
</a>

<!-- Financial Analytics -->
<a href="{{ route('admin.analytics.financial') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-blue-500/10 hover:to-blue-600/10 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 {{ request()->routeIs('admin.analytics.*') ? 'bg-gradient-to-r from-blue-500/10 to-blue-600/10 text-blue-600 dark:text-blue-400 border-r-4 border-blue-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.analytics.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['analytics'] ?? 'Analytics' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['analytics'] ?? 'التحليلات' }}</span>
    </span>
</a>

<!-- Payment Gateways -->
<a href="{{ route('admin.payment-gateways.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-green-500/10 hover:to-green-600/10 hover:text-green-600 dark:hover:text-green-400 transition-all duration-200 {{ request()->routeIs('admin.payment-gateways.*') ? 'bg-gradient-to-r from-green-500/10 to-green-600/10 text-green-600 dark:text-green-400 border-r-4 border-green-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.payment-gateways.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['paymentGateways'] ?? 'Payment Gateways' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['paymentGateways'] ?? 'بوابات الدفع' }}</span>
    </span>
</a>

<!-- KYC Verification -->
<a href="{{ route('admin.kyc.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-warning-500/10 hover:to-warning-600/10 hover:text-warning-600 dark:hover:text-warning-400 transition-all duration-200 {{ request()->routeIs('admin.kyc.*') ? 'bg-gradient-to-r from-warning-500/10 to-warning-600/10 text-warning-600 dark:text-warning-400 border-r-4 border-warning-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.kyc.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['kyc'] ?? 'KYC Verification' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['kyc'] ?? 'التحقق من الهوية' }}</span>
    </span>
</a>

<!-- Fraud Detection -->
<a href="{{ route('admin.fraud-detection.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-danger-500/10 hover:to-danger-600/10 hover:text-danger-600 dark:hover:text-danger-400 transition-all duration-200 {{ request()->routeIs('admin.fraud-detection.*') ? 'bg-gradient-to-r from-danger-500/10 to-danger-600/10 text-danger-600 dark:text-danger-400 border-r-4 border-danger-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.fraud-detection.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['fraud'] ?? 'Fraud Detection' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['fraud'] ?? 'كشف الاحتيال' }}</span>
    </span>
</a>

<!-- Regulatory Reports -->
<a href="{{ route('admin.regulatory-reports.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-indigo-500/10 hover:to-indigo-600/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200 {{ request()->routeIs('admin.regulatory-reports.*') ? 'bg-gradient-to-r from-indigo-500/10 to-indigo-600/10 text-indigo-600 dark:text-indigo-400 border-r-4 border-indigo-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.regulatory-reports.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['regulatory'] ?? 'Regulatory Reports' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['regulatory'] ?? 'التقارير التنظيمية' }}</span>
    </span>
</a>

<!-- System Settings -->
<a href="{{ route('admin.system.settings') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-gray-500/10 hover:to-gray-600/10 hover:text-gray-600 dark:hover:text-gray-400 transition-all duration-200 {{ request()->routeIs('admin.system.*') ? 'bg-gradient-to-r from-gray-500/10 to-gray-600/10 text-gray-600 dark:text-gray-400 border-r-4 border-gray-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.system.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['systemSettings'] ?? 'System Settings' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['systemSettings'] ?? 'إعدادات النظام' }}</span>
    </span>
</a>

<!-- Security & Compliance -->
<a href="{{ route('admin.security.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-warning-500/10 hover:to-warning-600/10 hover:text-warning-600 dark:hover:text-warning-400 transition-all duration-200 {{ request()->routeIs('admin.security.*') ? 'bg-gradient-to-r from-warning-500/10 to-warning-600/10 text-warning-600 dark:text-warning-400 border-r-4 border-warning-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.security.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['security'] ?? 'Security' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['security'] ?? 'الأمان' }}</span>
    </span>
</a>

<!-- Audit Logs -->
<a href="{{ route('admin.audit-logs.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-teal-500/10 hover:to-teal-600/10 hover:text-teal-600 dark:hover:text-teal-400 transition-all duration-200 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-gradient-to-r from-teal-500/10 to-teal-600/10 text-teal-600 dark:text-teal-400 border-r-4 border-teal-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.audit-logs.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['auditLogs'] ?? 'Audit Logs' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['auditLogs'] ?? 'سجلات التدقيق' }}</span>
    </span>
</a>

<!-- AI Analytics -->
<a href="{{ route('admin.ai-analytics.index') }}" 
   class="group flex items-center px-4 py-4 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-violet-500/10 hover:to-violet-600/10 hover:text-violet-600 dark:hover:text-violet-400 transition-all duration-200 {{ request()->routeIs('admin.ai-analytics.*') ? 'bg-gradient-to-r from-violet-500/10 to-violet-600/10 text-violet-600 dark:text-violet-400 border-r-4 border-violet-500' : '' }}">
    <div class="sidebar-icon flex-shrink-0 w-6 h-6 mr-4 transition-all duration-200 group-hover:scale-110 {{ request()->routeIs('admin.ai-analytics.*') ? 'opacity-100' : 'opacity-60' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
        </svg>
    </div>
    <span class="sidebar-text font-semibold transition-all duration-300">
        <span x-show="language === 'en'">{{ $translations['nav']['aiAnalytics'] ?? 'AI Analytics' }}</span>
        <span x-show="language === 'ar'">{{ $translations['nav']['aiAnalytics'] ?? 'الذكاء الاصطناعي' }}</span>
    </span>
</a>
