import { Head } from '@inertiajs/react'

export default function AdminKyc() {
  return (
    <>
      <Head title="إدارة KYC" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30">
        <div className="max-w-7xl mx-auto px-4 py-8">
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-gradient-amen mb-4">إدارة KYC</h1>
            <p className="text-xl text-stone-600">مراجعة طلبات التحقق من الهوية</p>
          </div>
          
          <div className="bg-white rounded-2xl shadow-xl p-8 border border-stone-100 text-center">
            <p className="text-stone-600">صفحة إدارة KYC قيد التطوير</p>
          </div>
        </div>
      </div>
    </>
  )
}
