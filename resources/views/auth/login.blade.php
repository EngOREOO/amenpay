<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - آمن</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    backgroundImage: {
                        'gradient-amen': 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    }
                }
            }
        }
    </script>
    <style>
        .bg-gradient-amen {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gradient-amen mb-2">آمن</h1>
                <p class="text-stone-600">تسجيل الدخول</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-stone-100">
                <form method="POST" action="/login" class="space-y-6">
                    @csrf
                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-stone-700 mb-2">
                            رقم الهاتف
                        </label>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                            placeholder="+966 50 123 4567"
                            required
                        />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-stone-700 mb-2">
                            كلمة المرور
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-12"
                                placeholder="أدخل كلمة المرور"
                                required
                            />
                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-stone-400 hover:text-stone-600"
                            >
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500"
                            />
                            <label for="remember" class="mr-2 text-sm text-stone-600">
                                تذكرني
                            </label>
                        </div>
                        <a href="/forgot-password" class="text-sm text-emerald-600 hover:text-emerald-700">
                            نسيت كلمة المرور؟
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-amen text-white py-3 px-6 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105"
                    >
                        تسجيل الدخول
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-stone-200"></div>
                    <span class="px-4 text-sm text-stone-500">أو</span>
                    <div class="flex-1 border-t border-stone-200"></div>
                </div>

                <!-- OTP Login -->
                <a
                    href="/login/otp"
                    class="w-full block text-center border-2 border-emerald-200 text-emerald-700 py-3 px-6 rounded-xl font-medium hover:bg-emerald-50 transition-all duration-300"
                >
                    تسجيل الدخول برمز OTP
                </a>

                <!-- Register Link -->
                <p class="mt-6 text-center text-sm text-stone-600">
                    ليس لديك حساب؟{' '}
                    <a href="/register" class="text-emerald-600 hover:text-emerald-700 font-medium">
                        إنشاء حساب جديد
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>
