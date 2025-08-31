<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - آمِـن | Amen Digital Identity</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', 'Cairo', sans-serif; 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        }
        
        .login-gradient { 
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 50%, #0f766e 100%); 
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .futuristic-bg {
            background: radial-gradient(circle at 20% 80%, rgba(20, 184, 166, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(13, 148, 136, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(15, 118, 110, 0.2) 0%, transparent 50%);
        }
        
        .floating-elements {
            animation: float 8s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(2deg); }
            66% { transform: translateY(-10px) rotate(-1deg); }
        }
        
        .glow-button {
            position: relative;
            overflow: hidden;
        }
        
        .glow-button::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.6) 0%, transparent 70%);
            animation: glowPulse 2s ease-in-out infinite;
        }
        
        @keyframes glowPulse {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.2); opacity: 1; }
        }
        
        .accent-teal {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
        }
        
        .text-accent {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="text-white overflow-x-hidden min-h-screen">
    <!-- Background Elements -->
    <div class="absolute inset-0 futuristic-bg"></div>
    
    <!-- Floating Tech Elements -->
    <div class="absolute top-20 left-20 w-32 h-32 bg-teal-500 bg-opacity-20 rounded-full floating-elements"></div>
    <div class="absolute bottom-20 right-20 w-48 h-48 bg-teal-600 bg-opacity-20 rounded-full floating-elements" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-24 h-24 bg-teal-400 bg-opacity-20 rounded-full floating-elements" style="animation-delay: 4s;"></div>
    
    <div class="min-h-screen flex items-center justify-center relative z-10 px-4">
        <div class="max-w-md w-full">
            <!-- Logo and Brand -->
            <div class="text-center mb-12">
                <div class="flex items-center justify-center space-x-4 mb-6">
                    <img src="{{ asset('logo.png') }}" alt="آمِـن" class="w-16 h-16">
                    <div class="text-left">
                        <h1 class="text-3xl font-bold text-accent">آمِـن</h1>
                        <p class="text-sm text-gray-300">Admin Portal</p>
                    </div>
                </div>
                <h2 class="text-4xl font-bold text-white mb-4">
                    Admin Access
                </h2>
                <p class="text-lg text-gray-300">
                    Secure access to Amen Digital Identity management
                </p>
            </div>

            <!-- Login Form -->
            <div class="glass-morphism p-8 rounded-3xl">
                @if(session('success'))
                    <div class="bg-green-500 bg-opacity-20 border border-green-400 text-green-200 px-4 py-3 rounded-xl mb-6">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-500 bg-opacity-20 border border-red-400 text-red-200 px-4 py-3 rounded-xl mb-6">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-white font-semibold mb-3 text-lg">
                            <i class="fas fa-envelope ml-2 text-teal-400"></i>
                            Email Address
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="w-full px-4 py-4 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-lg" 
                               placeholder="Enter your email address"
                               value="{{ old('email') }}">
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-white font-semibold mb-3 text-lg">
                            <i class="fas fa-lock ml-2 text-teal-400"></i>
                            Password
                        </label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="w-full px-4 py-4 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-lg" 
                               placeholder="Enter your password">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-5 w-5 text-teal-600 focus:ring-teal-500 border-gray-300 rounded bg-white bg-opacity-10">
                        <label for="remember" class="ml-3 block text-white text-lg">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full glow-button accent-teal text-white py-4 rounded-xl font-semibold text-lg hover:opacity-90 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                            <i class="fas fa-sign-in-alt ml-2"></i>
                            Sign In to Admin Portal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Back to Main Site -->
            <div class="text-center mt-8">
                <a href="{{ route('home') }}" class="text-teal-300 hover:text-teal-200 transition-all duration-300 text-lg">
                    <i class="fas fa-arrow-left ml-2"></i>
                    ← Back to Amen Main Site
                </a>
            </div>
            
            <!-- Security Notice -->
            <div class="text-center mt-6">
                <p class="text-gray-400 text-sm">
                    <i class="fas fa-shield-alt ml-1"></i>
                    Secure admin access with 256-bit encryption
                </p>
            </div>
        </div>
    </div>
</body>
</html>
