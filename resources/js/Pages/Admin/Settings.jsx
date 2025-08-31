import { Head } from '@inertiajs/react'

export default function AdminSettings() {
  return (
    <>
      <Head title="الإعدادات" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30">
        <div className="max-w-7xl mx-auto px-4 py-8">
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-gradient-amen mb-4">إعدادات النظام</h1>
            <p className="text-xl text-stone-600">تكوين النظام والإعدادات العامة</p>
          </div>
          
          <div className="bg-white rounded-2xl shadow-xl p-8 border border-stone-100 text-center">
            <p className="text-stone-600">صفحة الإعدادات قيد التطوير</p>
          </div>
        </div>
      </div>
    </>
  )
}
