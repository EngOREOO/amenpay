<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for rate limiting various types of
    | requests in the P-Finance application to prevent abuse and ensure
    | fair usage.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Authentication Rate Limits
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'max_attempts' => env('RATE_LIMIT_AUTH_MAX_ATTEMPTS', 5),
        'decay_minutes' => env('RATE_LIMIT_AUTH_DECAY_MINUTES', 15),
        'lockout_minutes' => env('RATE_LIMIT_AUTH_LOCKOUT_MINUTES', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Rate Limits
    |--------------------------------------------------------------------------
    */
    'otp' => [
        'max_attempts' => env('RATE_LIMIT_OTP_MAX_ATTEMPTS', 3),
        'decay_minutes' => env('RATE_LIMIT_OTP_DECAY_MINUTES', 5),
        'lockout_minutes' => env('RATE_LIMIT_OTP_LOCKOUT_MINUTES', 15),
        'phone_max_attempts' => env('RATE_LIMIT_OTP_PHONE_MAX_ATTEMPTS', 5),
        'phone_decay_minutes' => env('RATE_LIMIT_OTP_PHONE_DECAY_MINUTES', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Rate Limits
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'max_attempts' => env('RATE_LIMIT_PAYMENT_MAX_ATTEMPTS', 10),
        'decay_minutes' => env('RATE_LIMIT_PAYMENT_DECAY_MINUTES', 1),
        'amount_limit_per_hour' => env('RATE_LIMIT_PAYMENT_AMOUNT_PER_HOUR', 10000),
        'transaction_limit_per_hour' => env('RATE_LIMIT_PAYMENT_TRANSACTIONS_PER_HOUR', 50),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Rate Limits
    |--------------------------------------------------------------------------
    */
    'api' => [
        'max_attempts' => env('RATE_LIMIT_API_MAX_ATTEMPTS', 60),
        'decay_minutes' => env('RATE_LIMIT_API_DECAY_MINUTES', 1),
        'authenticated_max_attempts' => env('RATE_LIMIT_API_AUTHENTICATED_MAX_ATTEMPTS', 120),
        'authenticated_decay_minutes' => env('RATE_LIMIT_API_AUTHENTICATED_DECAY_MINUTES', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Rate Limits
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'max_attempts' => env('RATE_LIMIT_SMS_MAX_ATTEMPTS', 5),
        'decay_minutes' => env('RATE_LIMIT_SMS_DECAY_MINUTES', 1),
        'phone_max_attempts' => env('RATE_LIMIT_SMS_PHONE_MAX_ATTEMPTS', 10),
        'phone_decay_minutes' => env('RATE_LIMIT_SMS_PHONE_DECAY_MINUTES', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Rate Limits
    |--------------------------------------------------------------------------
    */
    'file_upload' => [
        'max_attempts' => env('RATE_LIMIT_FILE_UPLOAD_MAX_ATTEMPTS', 20),
        'decay_minutes' => env('RATE_LIMIT_FILE_UPLOAD_DECAY_MINUTES', 1),
        'size_limit_per_hour' => env('RATE_LIMIT_FILE_UPLOAD_SIZE_PER_HOUR', 100 * 1024 * 1024), // 100MB
        'file_limit_per_hour' => env('RATE_LIMIT_FILE_UPLOAD_FILES_PER_HOUR', 50),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Rate Limits
    |--------------------------------------------------------------------------
    */
    'default' => [
        'max_attempts' => env('RATE_LIMIT_DEFAULT_MAX_ATTEMPTS', 30),
        'decay_minutes' => env('RATE_LIMIT_DEFAULT_DECAY_MINUTES', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Rate Limits
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'max_attempts' => env('RATE_LIMIT_ADMIN_MAX_ATTEMPTS', 100),
        'decay_minutes' => env('RATE_LIMIT_ADMIN_DECAY_MINUTES', 1),
        'exempt_ips' => env('RATE_LIMIT_ADMIN_EXEMPT_IPS', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Rate Limits
    |--------------------------------------------------------------------------
    */
    'webhook' => [
        'max_attempts' => env('RATE_LIMIT_WEBHOOK_MAX_ATTEMPTS', 100),
        'decay_minutes' => env('RATE_LIMIT_WEBHOOK_DECAY_MINUTES', 1),
        'ip_whitelist' => env('RATE_LIMIT_WEBHOOK_IP_WHITELIST', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Rate Limits
    |--------------------------------------------------------------------------
    */
    'search' => [
        'max_attempts' => env('RATE_LIMIT_SEARCH_MAX_ATTEMPTS', 20),
        'decay_minutes' => env('RATE_LIMIT_SEARCH_DECAY_MINUTES', 1),
        'complex_search_max_attempts' => env('RATE_LIMIT_SEARCH_COMPLEX_MAX_ATTEMPTS', 10),
        'complex_search_decay_minutes' => env('RATE_LIMIT_SEARCH_COMPLEX_DECAY_MINUTES', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Generation Rate Limits
    |--------------------------------------------------------------------------
    */
    'reports' => [
        'max_attempts' => env('RATE_LIMIT_REPORTS_MAX_ATTEMPTS', 10),
        'decay_minutes' => env('RATE_LIMIT_REPORTS_DECAY_MINUTES', 5),
        'export_max_attempts' => env('RATE_LIMIT_REPORTS_EXPORT_MAX_ATTEMPTS', 5),
        'export_decay_minutes' => env('RATE_LIMIT_REPORTS_EXPORT_DECAY_MINUTES', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Rate Limiting Settings
    |--------------------------------------------------------------------------
    */
    'global' => [
        'enabled' => env('RATE_LIMIT_GLOBAL_ENABLED', true),
        'max_attempts' => env('RATE_LIMIT_GLOBAL_MAX_ATTEMPTS', 1000),
        'decay_minutes' => env('RATE_LIMIT_GLOBAL_DECAY_MINUTES', 1),
        'exempt_ips' => env('RATE_LIMIT_GLOBAL_EXEMPT_IPS', ''),
        'exempt_user_agents' => env('RATE_LIMIT_GLOBAL_EXEMPT_USER_AGENTS', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Response Messages
    |--------------------------------------------------------------------------
    */
    'messages' => [
        'ar' => [
            'auth' => 'تم تجاوز الحد الأقصى لمحاولات تسجيل الدخول. يرجى المحاولة بعد فترة.',
            'otp' => 'تم تجاوز الحد الأقصى لطلبات رمز التحقق. يرجى المحاولة بعد 5 دقائق.',
            'payment' => 'تم تجاوز الحد الأقصى لطلبات الدفع. يرجى المحاولة بعد دقيقة.',
            'api' => 'تم تجاوز الحد الأقصى لطلبات API. يرجى المحاولة بعد دقيقة.',
            'sms' => 'تم تجاوز الحد الأقصى لرسائل SMS. يرجى المحاولة بعد دقيقة.',
            'file_upload' => 'تم تجاوز الحد الأقصى لرفع الملفات. يرجى المحاولة بعد دقيقة.',
            'default' => 'تم تجاوز الحد الأقصى للطلبات. يرجى المحاولة بعد دقيقة.',
        ],
        'en' => [
            'auth' => 'Too many login attempts. Please try again later.',
            'otp' => 'Too many OTP requests. Please try again in 5 minutes.',
            'payment' => 'Too many payment requests. Please try again in 1 minute.',
            'api' => 'Too many API requests. Please try again in 1 minute.',
            'sms' => 'Too many SMS requests. Please try again in 1 minute.',
            'file_upload' => 'Too many file upload requests. Please try again in 1 minute.',
            'default' => 'Too many requests. Please try again in 1 minute.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Headers
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'enabled' => env('RATE_LIMIT_HEADERS_ENABLED', true),
        'limit_header' => 'X-RateLimit-Limit',
        'remaining_header' => 'X-RateLimit-Remaining',
        'reset_header' => 'X-RateLimit-Reset',
        'retry_after_header' => 'Retry-After',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('RATE_LIMIT_LOGGING_ENABLED', true),
        'level' => env('RATE_LIMIT_LOGGING_LEVEL', 'warning'),
        'log_exempted' => env('RATE_LIMIT_LOG_EXEMPTED', false),
        'log_headers' => env('RATE_LIMIT_LOG_HEADERS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Cache
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'driver' => env('RATE_LIMIT_CACHE_DRIVER', 'redis'),
        'prefix' => env('RATE_LIMIT_CACHE_PREFIX', 'rate_limit:'),
        'ttl' => env('RATE_LIMIT_CACHE_TTL', 3600),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Exemptions
    |--------------------------------------------------------------------------
    */
    'exemptions' => [
        'health_check' => true,
        'ping' => true,
        'metrics' => true,
        'admin_users' => true,
        'whitelisted_ips' => env('RATE_LIMIT_WHITELISTED_IPS', ''),
        'whitelisted_routes' => [
            'health',
            'ping',
            'metrics',
            'webhooks/*',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Monitoring
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'enabled' => env('RATE_LIMIT_MONITORING_ENABLED', true),
        'metrics_enabled' => env('RATE_LIMIT_METRICS_ENABLED', true),
        'alert_threshold' => env('RATE_LIMIT_ALERT_THRESHOLD', 0.8),
        'alert_channels' => env('RATE_LIMIT_ALERT_CHANNELS', 'log'),
    ],
];
