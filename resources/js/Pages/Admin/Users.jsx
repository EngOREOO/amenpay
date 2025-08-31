import { Head } from '@inertiajs/react'

export default function AdminUsers() {
  return (
    <>
      <Head title="إدارة المستخدمين" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30">
        <div className="max-w-7xl mx-auto px-4 py-8">
          {/* Header */}
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-gradient-amen mb-4">إدارة المستخدمين</h1>
            <p className="text-xl text-stone-600">عرض وإدارة جميع المستخدمين في النظام</p>
          </div>

          {/* Actions Bar */}
          <div className="bg-white rounded-2xl shadow-xl p-6 border border-stone-100 mb-8">
            <div className="flex flex-col sm:flex-row gap-4 items-center justify-between">
              <div className="flex-1">
                <input
                  type="text"
                  placeholder="البحث عن مستخدم..."
                  className="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                />
              </div>
              <div className="flex gap-3">
                <button className="bg-emerald-600 text-white py-3 px-6 rounded-xl font-medium hover:bg-emerald-700 transition-colors">
                  إضافة مستخدم جديد
                </button>
                <button className="bg-blue-600 text-white py-3 px-6 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                  تصدير البيانات
                </button>
              </div>
            </div>
          </div>

          {/* Users Table */}
          <div className="bg-white rounded-2xl shadow-xl border border-stone-100 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead className="bg-stone-50">
                  <tr>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">المستخدم</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">رقم الهاتف</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">البريد الإلكتروني</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">الحالة</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">تاريخ التسجيل</th>
                    <th className="px-6 py-4 text-right text-sm font-semibold text-stone-700">الإجراءات</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-stone-200">
                  <tr className="hover:bg-stone-50">
                    <td className="px-6 py-4">
                      <div className="flex items-center">
                        <div className="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                          <span className="text-emerald-600 font-semibold">أ</span>
                        </div>
                        <div className="mr-3">
                          <div className="text-sm font-medium text-stone-900">أحمد محمد</div>
                          <div className="text-sm text-stone-500">مستخدم عادي</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 text-sm text-stone-900">+966 50 123 4567</td>
                    <td className="px-6 py-4 text-sm text-stone-900">ahmed@example.com</td>
                    <td className="px-6 py-4">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        نشط
                      </span>
                    </td>
                    <td className="px-6 py-4 text-sm text-stone-500">2025-08-23</td>
                    <td className="px-6 py-4 text-sm font-medium">
                      <div className="flex gap-2">
                        <button className="text-blue-600 hover:text-blue-900">تعديل</button>
                        <button className="text-emerald-600 hover:text-emerald-900">تفعيل</button>
                        <button className="text-red-600 hover:text-red-900">إيقاف</button>
                      </div>
                    </td>
                  </tr>
                  <tr className="hover:bg-stone-50">
                    <td className="px-6 py-4">
                      <div className="flex items-center">
                        <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                          <span className="text-blue-600 font-semibold">س</span>
                        </div>
                        <div className="mr-3">
                          <div className="text-sm font-medium text-stone-900">سارة أحمد</div>
                          <div className="text-sm text-stone-500">مستخدم عادي</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 text-sm text-stone-900">+966 55 987 6543</td>
                    <td className="px-6 py-4 text-sm text-stone-900">sara@example.com</td>
                    <td className="px-6 py-4">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        في انتظار التحقق
                      </span>
                    </td>
                    <td className="px-6 py-4 text-sm text-stone-500">2025-08-22</td>
                    <td className="px-6 py-4 text-sm font-medium">
                      <div className="flex gap-2">
                        <button className="text-blue-600 hover:text-blue-900">تعديل</button>
                        <button className="text-emerald-600 hover:text-emerald-900">تفعيل</button>
                        <button className="text-red-600 hover:text-red-900">إيقاف</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            {/* Empty State */}
            <div className="text-center py-12 text-stone-500">
              <p>لا توجد مستخدمين لعرضهم حالياً</p>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
