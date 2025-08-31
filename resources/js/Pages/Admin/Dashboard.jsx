import { Head } from '@inertiajs/react'

export default function AdminDashboard() {
  return (
    <>
      <Head title="لوحة تحكم المدير" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30">
        <div className="max-w-7xl mx-auto px-4 py-8">
          {/* Header */}
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-gradient-amen mb-4">لوحة تحكم المدير</h1>
            <p className="text-xl text-stone-600">إدارة النظام والمستخدمين</p>
          </div>

          {/* Stats Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            {/* Total Users */}
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-lg font-semibold text-stone-800">إجمالي المستخدمين</h3>
                <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                  <svg className="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                  </svg>
                </div>
              </div>
              <p className="text-3xl font-bold text-stone-900 mb-2">0</p>
              <p className="text-sm text-stone-500">مستخدم نشط</p>
            </div>

            {/* Total Transactions */}
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-lg font-semibold text-stone-800">إجمالي المعاملات</h3>
                <div className="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                  <svg className="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
              </div>
              <p className="text-3xl font-bold text-stone-900 mb-2">0</p>
              <p className="text-sm text-stone-500">معاملة اليوم</p>
            </div>

            {/* Revenue */}
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-lg font-semibold text-stone-800">الإيرادات</h3>
                <div className="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                  <svg className="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                  </svg>
                </div>
              </div>
              <p className="text-3xl font-bold text-stone-900 mb-2">0.00 ريال</p>
              <p className="text-sm text-stone-500">إجمالي الإيرادات</p>
            </div>

            {/* Fraud Alerts */}
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-lg font-semibold text-stone-800">تنبيهات الاحتيال</h3>
                <div className="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                  <svg className="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  </svg>
                </div>
              </div>
              <p className="text-3xl font-bold text-stone-900 mb-2">0</p>
              <p className="text-sm text-stone-500">تنبيه نشط</p>
            </div>
          </div>

          {/* Quick Actions */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {/* User Management */}
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <h3 className="text-xl font-semibold text-stone-800 mb-4">إدارة المستخدمين</h3>
              <div className="space-y-3">
                <button className="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                  عرض جميع المستخدمين
                </button>
                <button className="w-full bg-emerald-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-emerald-700 transition-colors">
                  إضافة مستخدم جديد
                </button>
                <button className="w-full bg-amber-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-amber-700 transition-colors">
                  مراجعة KYC
                </button>
              </div>
            </div>

            {/* Transaction Management */}
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <h3 className="text-xl font-semibold text-stone-800 mb-4">إدارة المعاملات</h3>
              <div className="space-y-3">
                <button className="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                  عرض المعاملات
                </button>
                <button className="w-full bg-purple-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-purple-700 transition-colors">
                  تقارير المعاملات
                </button>
                <button className="w-full bg-indigo-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-indigo-700 transition-colors">
                  إعدادات الدفع
                </button>
              </div>
            </div>

            {/* System Management */}
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <h3 className="text-xl font-semibold text-stone-800 mb-4">إدارة النظام</h3>
              <div className="space-y-3">
                <button className="w-full bg-gray-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-gray-700 transition-colors">
                  إعدادات النظام
                </button>
                <button className="w-full bg-red-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-red-700 transition-colors">
                  سجلات الأمان
                </button>
                <button className="w-full bg-teal-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-teal-700 transition-colors">
                  النسخ الاحتياطية
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
