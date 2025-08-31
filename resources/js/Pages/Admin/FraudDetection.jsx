import { Head } from '@inertiajs/react'

export default function AdminFraudDetection() {
  return (
    <>
      <Head title="كشف الاحتيال" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30">
        <div className="max-w-7xl mx-auto px-4 py-8">
          {/* Header */}
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-gradient-amen mb-4">كشف الاحتيال</h1>
            <p className="text-xl text-stone-600">نظام الذكاء الاصطناعي لكشف المعاملات المشبوهة</p>
          </div>

          {/* Stats */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="text-center">
                <p className="text-3xl font-bold text-red-600">0</p>
                <p className="text-sm text-stone-500">تنبيهات عالية الخطورة</p>
              </div>
            </div>
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="text-center">
                <p className="text-3xl font-bold text-yellow-600">0</p>
                <p className="text-sm text-stone-500">تنبيهات متوسطة الخطورة</p>
              </div>
            </div>
            <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100">
              <div className="text-center">
                <p className="text-3xl font-bold text-green-600">0</p>
                <p className="text-sm text-stone-500">معاملات آمنة</p>
              </div>
            </div>
          </div>

          {/* AI Model Status */}
          <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100 mb-8">
            <h3 className="text-xl font-semibold text-stone-800 mb-4">حالة نموذج الذكاء الاصطناعي</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <p className="text-sm text-stone-600 mb-2">دقة النموذج</p>
                <div className="w-full bg-stone-200 rounded-full h-3">
                  <div className="bg-emerald-600 h-3 rounded-full" style={{ width: '95%' }}></div>
                </div>
                <p className="text-sm text-stone-500 mt-1">95%</p>
              </div>
              <div>
                <p className="text-sm text-stone-600 mb-2">آخر تحديث</p>
                <p className="text-sm text-stone-900">2025-08-23 10:00</p>
              </div>
            </div>
          </div>

          {/* Alerts Table */}
          <div className="bg-white rounded-2xl shadow-xl border border-stone-100 overflow-hidden">
            <div className="p-6 border-b border-stone-200">
              <h3 className="text-xl font-semibold text-stone-800">التنبيهات النشطة</h3>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead className="bg-stone-50">
                  <tr>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">مستوى الخطورة</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">نوع التنبيه</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">المستخدم</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">التفاصيل</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">التاريخ</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">الإجراءات</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-stone-200">
                  <tr className="hover:bg-stone-50">
                    <td className="px-6 py-4">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        عالية
                      </span>
                    </td>
                    <td className="px-6 py-4 text-sm text-stone-900">نمط مشبوه</td>
                    <td className="px-6 py-4 text-sm text-stone-900">أحمد محمد</td>
                    <td className="px-6 py-4 text-sm text-stone-600">معاملات متعددة في وقت قصير</td>
                    <td className="px-6 py-4 text-sm text-stone-500">2025-08-23 15:30</td>
                    <td className="px-6 py-4 text-sm font-medium">
                      <div className="flex gap-2">
                        <button className="text-blue-600 hover:text-blue-900">عرض</button>
                        <button className="text-emerald-600 hover:text-emerald-900">إلغاء التنبيه</button>
                        <button className="text-red-600 hover:text-red-900">إيقاف الحساب</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            {/* Empty State */}
            <div className="text-center py-12 text-stone-500">
              <p>لا توجد تنبيهات نشطة حالياً</p>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
