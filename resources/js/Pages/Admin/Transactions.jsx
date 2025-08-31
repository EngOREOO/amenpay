import { Head } from '@inertiajs/react'

export default function AdminTransactions() {
  return (
    <>
      <Head title="إدارة المعاملات" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30">
        <div className="max-w-7xl mx-auto px-4 py-8">
          {/* Header */}
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-gradient-amen mb-4">إدارة المعاملات</h1>
            <p className="text-xl text-stone-600">مراقبة وإدارة جميع المعاملات في النظام</p>
          </div>

          {/* Stats Cards */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="text-center">
                <p className="text-2xl font-bold text-stone-900">0</p>
                <p className="text-sm text-stone-500">إجمالي المعاملات</p>
              </div>
            </div>
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="text-center">
                <p className="text-2xl font-bold text-emerald-600">0.00 ريال</p>
                <p className="text-sm text-stone-500">إجمالي المبالغ</p>
              </div>
            </div>
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="text-center">
                <p className="text-2xl font-bold text-blue-600">0</p>
                <p className="text-sm text-stone-500">معاملات اليوم</p>
              </div>
            </div>
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="text-center">
                <p className="text-2xl font-bold text-red-600">0</p>
                <p className="text-sm text-stone-500">معاملات مرفوضة</p>
              </div>
            </div>
          </div>

          {/* Filters */}
          <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100 mb-8">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
              <select className="px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option>جميع الحالات</option>
                <option>مكتملة</option>
                <option>قيد المعالجة</option>
                <option>مرفوضة</option>
              </select>
              <select className="px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option>جميع الأنواع</option>
                <option>تحويل</option>
                <option>دفع</option>
                <option>إيداع</option>
              </select>
              <input
                type="date"
                className="px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
              />
              <button className="bg-emerald-600 text-white py-3 px-6 rounded-xl font-medium hover:bg-emerald-700 transition-colors">
                تطبيق الفلاتر
              </button>
            </div>
          </div>

          {/* Transactions Table */}
          <div className="bg-white rounded-2xl shadow-xl border border-stone-100 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead className="bg-stone-50">
                  <tr>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">معرف المعاملة</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">المستخدم</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">النوع</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">المبلغ</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">الحالة</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">التاريخ</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">الإجراءات</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-stone-200">
                  <tr className="hover:bg-stone-50">
                    <td className="px-6 py-4 text-sm font-mono text-stone-900">TXN-001</td>
                    <td className="px-6 py-4">
                      <div className="flex items-center">
                        <div className="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                          <span className="text-emerald-600 font-semibold text-xs">أ</span>
                        </div>
                        <span className="mr-2 text-sm text-stone-900">أحمد محمد</span>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        تحويل
                      </span>
                    </td>
                    <td className="px-6 py-4 text-sm font-semibold text-stone-900">500.00 ريال</td>
                    <td className="px-6 py-4">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        مكتملة
                      </span>
                    </td>
                    <td className="px-6 py-4 text-sm text-stone-500">2025-08-23 14:30</td>
                    <td className="px-6 py-4 text-sm font-medium">
                      <div className="flex gap-2">
                        <button className="text-blue-600 hover:text-blue-900">عرض</button>
                        <button className="text-emerald-600 hover:text-emerald-900">موافقة</button>
                        <button className="text-red-600 hover:text-red-900">رفض</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            {/* Empty State */}
            <div className="text-center py-12 text-stone-500">
              <p>لا توجد معاملات لعرضها حالياً</p>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
