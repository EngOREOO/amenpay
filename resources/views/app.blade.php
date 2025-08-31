<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amen Pay') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Custom Styles -->
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .text-gradient-amen {
            background: linear-gradient(135deg, #10b981, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .bg-gradient-amen {
            background: linear-gradient(135deg, #10b981, #d97706);
        }
    </style>
</head>
<body class="font-cairo antialiased">
    <div id="app">
        <!-- Your content will go here -->
        <div class="min-h-screen bg-gradient-to-br from-stone-50 to-amber-50/30">
            <div class="container mx-auto px-4 py-8">
                <h1 class="text-4xl font-bold text-center text-gradient-amen mb-8">
                    {{ config('app.name', 'Amen Pay') }}
                </h1>
                <p class="text-center text-stone-600 text-lg">
                    Welcome to Amen Pay - Secure Payment Solutions
                </p>
            </div>
        </div>
    </div>
</body>
</html>

