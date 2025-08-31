import { useState } from 'react'
import { Head, Link, useForm } from '@inertiajs/react'

export default function Register() {
  const [showPassword, setShowPassword] = useState(false)
  const [showConfirmPassword, setShowConfirmPassword] = useState(false)
  
  const { data, setData, post, processing, errors } = useForm({
    first_name: '',
    last_name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
  })

  const submit = (e) => {
    e.preventDefault()
    post('/register')
  }

  return (
    <>
      <Head title="إنشاء حساب جديد" />
      
      <div className="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30 flex items-center justify-center p-4">
        <div className="max-w-md w-full">
          {/* Logo */}
          <div className="text-center mb-8">
            <h1 className="text-4xl font-bold text-gradient-amen mb-2">آمن</h1>
            <p className="text-stone-600">إنشاء حساب جديد</p>
          </div>

          {/* Register Form */}
          <div className="bg-white rounded-2xl shadow-xl p-8 border border-stone-100">
            <form onSubmit={submit} className="space-y-6">
              {/* First Name */}
              <div>
                <label htmlFor="first_name" className="block text-sm font-medium text-stone-700 mb-2">
                  الاسم الأول
                </label>
                <input
                  id="first_name"
                  type="text"
                  value={data.first_name}
                  onChange={(e) => setData('first_name', e.target.value)}
                  className="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                  placeholder="أدخل اسمك الأول"
                  required
                />
                {errors.first_name && (
                  <p className="mt-1 text-sm text-red-600">{errors.first_name}</p>
                )}
              </div>

              {/* Last Name */}
              <div>
                <label htmlFor="last_name" className="block text-sm font-medium text-stone-700 mb-2">
                  اسم العائلة
                </label>
                <input
                  id="last_name"
                  type="text"
                  value={data.last_name}
                  onChange={(e) => setData('last_name', e.target.value)}
                  className="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                  placeholder="أدخل اسم العائلة"
                  required
                />
                {errors.last_name && (
                  <p className="mt-1 text-sm text-red-600">{errors.last_name}</p>
                )}
              </div>

              {/* Phone Number */}
              <div>
                <label htmlFor="phone" className="block text-sm font-medium text-stone-700 mb-2">
                  رقم الهاتف
                </label>
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

              {/* Email */}
              <div>
                <label htmlFor="email" className="block text-sm font-medium text-stone-700 mb-2">
                  البريد الإلكتروني
                </label>
                <input
                  id="email"
                  type="email"
                  value={data.email}
                  onChange={(e) => setData('email', e.target.value)}
                  className="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                  placeholder="example@email.com"
                  required
                />
                {errors.email && (
                  <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                )}
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

              {/* Confirm Password */}
              <div>
                <label htmlFor="password_confirmation" className="block text-sm font-medium text-stone-700 mb-2">
                  تأكيد كلمة المرور
                </label>
                <div className="relative">
                  <input
                    id="password_confirmation"
                    type={showConfirmPassword ? 'text' : 'password'}
                    value={data.password_confirmation}
                    onChange={(e) => setData('password_confirmation', e.target.value)}
                    className="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-12"
                    placeholder="أعد إدخال كلمة المرور"
                    required
                  />
                  <button
                    type="button"
                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                    className="absolute left-3 top-1/2 transform -translate-y-1/2 text-stone-400 hover:text-stone-600"
                  >
                    {showConfirmPassword ? (
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
                  {errors.password_confirmation && (
                    <p className="mt-1 text-sm text-red-600">{errors.password_confirmation}</p>
                  )}
                </div>
              </div>

              {/* Terms and Conditions */}
              <div className="flex items-start">
                <input
                  id="terms"
                  type="checkbox"
                  checked={data.terms}
                  onChange={(e) => setData('terms', e.target.checked)}
                  className="mt-1 rounded border-stone-300 text-emerald-600 focus:ring-emerald-500"
                  required
                />
                <label htmlFor="terms" className="mr-2 text-sm text-stone-600">
                  أوافق على{' '}
                  <Link href="/terms" className="text-emerald-600 hover:text-emerald-700 font-medium">
                    الشروط والأحكام
                  </Link>
                  {' '}و{' '}
                  <Link href="/privacy" className="text-emerald-600 hover:text-emerald-700 font-medium">
                    سياسة الخصوصية
                  </Link>
                </label>
                {errors.terms && (
                  <p className="mt-1 text-sm text-red-600">{errors.terms}</p>
                )}
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={processing}
                className="w-full bg-gradient-amen text-white py-3 px-6 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {processing ? 'جاري إنشاء الحساب...' : 'إنشاء حساب جديد'}
              </button>
            </form>

            {/* Divider */}
            <div className="my-6 flex items-center">
              <div className="flex-1 border-t border-stone-200"></div>
              <span className="px-4 text-sm text-stone-500">أو</span>
              <div className="flex-1 border-t border-stone-200"></div>
            </div>

            {/* OTP Registration */}
            <Link
              href="/register/otp"
              className="w-full block text-center border-2 border-emerald-200 text-emerald-700 py-3 px-6 rounded-xl font-medium hover:bg-emerald-50 transition-all duration-300"
            >
              التسجيل برمز OTP
            </Link>

            {/* Login Link */}
            <p className="mt-6 text-center text-sm text-stone-600">
              لديك حساب بالفعل؟{' '}
              <Link href="/login" className="text-emerald-600 hover:text-emerald-700 font-medium">
                تسجيل الدخول
              </Link>
            </p>
          </div>
        </div>
      </div>
    </>
  )
}
