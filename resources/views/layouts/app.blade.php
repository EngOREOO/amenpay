<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amen Pay') }} - @yield('title', 'مستقبل المدفوعات الآمنة')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('ui/styles.css') }}">
    
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Custom JavaScript -->
    <script src="{{ asset('ui/script.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
