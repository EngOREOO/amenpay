import { useState } from 'react'
import { Head, Link, useForm } from '@inertiajs/react'

export default function Login() {
  const [showPassword, setShowPassword] = useState(false)
  
  const { data, setData, post, processing, errors } = useForm({
    phone: '',
    password: '',
    remember: false,
  })

  const submit = (e) => {
    e.preventDefault()
    post('/login')
  }

  return (
    <>
      <Head title="تسجيل الدخول" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30 flex items-center justify-center p-4">
        <div className="max-w-md w-full">
          {/* Logo */}
          <div className="text-center mb-8">
            <h1 className="text-4xl font-bold text-gradient-amen mb-2">آمن</h1>
            <p className="text-stone-600">تسجيل الدخول إلى حسابك</p>
          </div>

          {/* Login Form */}
          <div className="bg-white rounded-2xl shadow-xl p-8 border border-stone-100">
            <form onSubmit={submit} className="space-y-6">
              {/* Phone Number */}
              <div>
                <label htmlFor="phone" className="block text-sm font-medium text-stone-700 mb-2">
                  رقم الهاتف
                </label>
                <div className="relative">
                  <input
                    id="phone"
                    type="tel"
                    value={data.phone}
                    onChange={(e) => setData('phone', e.target.value)}
                    className="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                    placeholder="+966 50 123 4567"
                    required
                  />
                  {errors.phone && (
                    <p className="mt-1 text-sm text-red-600">{errors.phone}</p>
                  )}
                </div>
              </div>

              {/* Password */}
              <div>
                <label htmlFor="password" className="block text-sm font-medium text-stone-700 mb-2">
                  كلمة المرور
                </label>
                <div className="relative">
                  <input
                    id="password"
                    type={showPassword ? 'text' : 'password'}
                    value={data.password}
                    onChange={(e) => setData('password', e.target.value)}
                    className="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-12"
                    placeholder="أدخل كلمة المرور"
                    required
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute left-3 top-1/2 transform -translate-y-1/2 text-stone-400 hover:text-stone-600"
                  >
                    {showPassword ? (
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                      </svg>
                    ) : (
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    )}
                  </button>
                  {errors.password && (
                    <p className="mt-1 text-sm text-red-600">{errors.password}</p>
                  )}
                </div>
              </div>

              {/* Remember Me & Forgot Password */}
              <div className="flex items-center justify-between">
                <label className="flex items-center">
                  <input
                    type="checkbox"
                    checked={data.remember}
                    onChange={(e) => setData('remember', e.target.checked)}
                    className="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500"
                  />
                  <span className="ml-2 text-sm text-stone-600">تذكرني</span>
                </label>
                <Link
                  href="/forgot-password"
                  className="text-sm text-emerald-600 hover:text-emerald-700 font-medium"
                >
                  نسيت كلمة المرور؟
                </Link>
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={processing}
                className="w-full bg-gradient-amen text-white py-3 px-6 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {processing ? 'جاري تسجيل الدخول...' : 'تسجيل الدخول'}
              </button>
            </form>

            {/* Divider */}
            <div className="my-6 flex items-center">
              <div className="flex-1 border-t border-stone-200"></div>
              <span className="px-4 text-sm text-stone-500">أو</span>
              <div className="flex-1 border-t border-stone-200"></div>
            </div>

            {/* OTP Login */}
            <Link
              href="/login/otp"
              className="w-full block text-center border-2 border-emerald-200 text-emerald-700 py-3 px-6 rounded-xl font-medium hover:bg-emerald-50 transition-all duration-300"
            >
              تسجيل الدخول برمز OTP
            </Link>

            {/* Register Link */}
            <p className="mt-6 text-center text-sm text-stone-600">
              ليس لديك حساب؟{' '}
              <Link href="/register" className="text-emerald-600 hover:text-emerald-700 font-medium">
                إنشاء حساب جديد
              </Link>
            </p>
          </div>
        </div>
      </div>
    </>
  )
}
