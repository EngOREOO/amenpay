<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Communication Service Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for various communication services
    | used in the P-Finance application, including SMS, email, and push notifications.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'enabled' => env('SMS_ENABLED', true),
        'default_provider' => env('SMS_DEFAULT_PROVIDER', 'twilio'),
        
        'providers' => [
            'twilio' => [
                'enabled' => env('TWILIO_ENABLED', false),
                'account_sid' => env('TWILIO_ACCOUNT_SID'),
                'auth_token' => env('TWILIO_AUTH_TOKEN'),
                'from_number' => env('TWILIO_FROM_NUMBER'),
                'webhook_url' => env('TWILIO_WEBHOOK_URL'),
            ],
            
            'nexmo' => [
                'enabled' => env('NEXMO_ENABLED', false),
                'api_key' => env('NEXMO_API_KEY'),
                'api_secret' => env('NEXMO_API_SECRET'),
                'from_number' => env('NEXMO_FROM_NUMBER'),
                'webhook_url' => env('NEXMO_WEBHOOK_URL'),
            ],
            
            'saudi_telecom' => [
                'enabled' => env('SAUDI_TELECOM_ENABLED', false),
                'api_key' => env('SAUDI_TELECOM_API_KEY'),
                'sender_id' => env('SAUDI_TELECOM_SENDER_ID'),
                'api_url' => env('SAUDI_TELECOM_API_URL', 'https://api.stc.com.sa/sms/v1'),
                'webhook_url' => env('SAUDI_TELECOM_WEBHOOK_URL'),
            ],
        ],
        
        'templates' => [
            'otp' => [
                'ar' => 'رمز التحقق الخاص بك هو: {otp}. صالح لمدة 5 دقائق.',
                'en' => 'Your verification code is: {otp}. Valid for 5 minutes.'
            ],
            'welcome' => [
                'ar' => 'مرحباً بك في P-Finance! نتمنى لك تجربة ممتعة.',
                'en' => 'Welcome to P-Finance! We hope you have a great experience.'
            ],
            'transaction' => [
                'ar' => 'تم {action} مبلغ {amount} ريال. الرصيد الحالي: {balance} ريال.',
                'en' => '{action} amount {amount} SAR. Current balance: {balance} SAR.'
            ],
        ],
        
        'rate_limit' => [
            'per_minute' => env('SMS_RATE_LIMIT_PER_MINUTE', 10),
            'per_hour' => env('SMS_RATE_LIMIT_PER_HOUR', 100),
            'per_day' => env('SMS_RATE_LIMIT_PER_DAY', 1000),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Configuration
    |--------------------------------------------------------------------------
    */
    'email' => [
        'enabled' => env('EMAIL_ENABLED', true),
        'default_provider' => env('EMAIL_DEFAULT_PROVIDER', 'smtp'),
        
        'providers' => [
            'smtp' => [
                'enabled' => env('MAIL_MAILER') === 'smtp',
                'host' => env('MAIL_HOST'),
                'port' => env('MAIL_PORT'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'encryption' => env('MAIL_ENCRYPTION'),
            ],
            
            'sendgrid' => [
                'enabled' => env('SENDGRID_ENABLED', false),
                'api_key' => env('SENDGRID_API_KEY'),
                'from_email' => env('SENDGRID_FROM_EMAIL'),
                'from_name' => env('SENDGRID_FROM_NAME'),
            ],
            
            'mailgun' => [
                'enabled' => env('MAILGUN_ENABLED', false),
                'domain' => env('MAILGUN_DOMAIN'),
                'secret' => env('MAILGUN_SECRET'),
                'endpoint' => env('MAILGUN_ENDPOINT'),
            ],
        ],
        
        'templates' => [
            'welcome' => 'emails.welcome',
            'transaction' => 'emails.transaction',
            'password_reset' => 'emails.password-reset',
            'verification' => 'emails.verification',
            'announcement' => 'emails.announcement',
        ],
        
        'defaults' => [
            'from_email' => env('MAIL_FROM_ADDRESS', 'noreply@p-finance.com'),
            'from_name' => env('MAIL_FROM_NAME', 'P-Finance'),
            'reply_to' => env('MAIL_REPLY_TO', 'support@p-finance.com'),
        ],
        
        'rate_limit' => [
            'per_minute' => env('EMAIL_RATE_LIMIT_PER_MINUTE', 60),
            'per_hour' => env('EMAIL_RATE_LIMIT_PER_HOUR', 1000),
            'per_day' => env('EMAIL_RATE_LIMIT_PER_DAY', 10000),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Notification Configuration
    |--------------------------------------------------------------------------
    */
    'push' => [
        'enabled' => env('PUSH_NOTIFICATIONS_ENABLED', true),
        'default_provider' => env('PUSH_DEFAULT_PROVIDER', 'fcm'),
        
        'providers' => [
            'fcm' => [
                'enabled' => env('FCM_ENABLED', true),
                'server_key' => env('FCM_SERVER_KEY'),
                'project_id' => env('FCM_PROJECT_ID'),
                'api_url' => 'https://fcm.googleapis.com/fcm/send',
            ],
            
            'apns' => [
                'enabled' => env('APNS_ENABLED', false),
                'certificate_path' => env('APNS_CERTIFICATE_PATH'),
                'passphrase' => env('APNS_PASSPHRASE'),
                'environment' => env('APNS_ENVIRONMENT', 'sandbox'),
                'team_id' => env('APNS_TEAM_ID'),
                'key_id' => env('APNS_KEY_ID'),
            ],
        ],
        
        'templates' => [
            'transaction' => [
                'ar' => 'تم {action} مبلغ {amount} ريال',
                'en' => '{action} amount {amount} SAR'
            ],
            'security' => [
                'ar' => 'تنبيه أمني: {message}',
                'en' => 'Security Alert: {message}'
            ],
            'announcement' => [
                'ar' => 'إعلان جديد: {title}',
                'en' => 'New Announcement: {title}'
            ],
        ],
        
        'settings' => [
            'default_sound' => 'default',
            'default_badge' => 1,
            'priority' => 'high',
            'time_to_live' => 86400, // 24 hours
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    */
    'otp' => [
        'enabled' => env('OTP_ENABLED', true),
        'length' => env('OTP_LENGTH', 6),
        'expiry_minutes' => env('OTP_EXPIRY_MINUTES', 5),
        'max_attempts' => env('OTP_MAX_ATTEMPTS', 3),
        'lockout_minutes' => env('OTP_LOCKOUT_MINUTES', 15),
        
        'types' => [
            'login' => [
                'enabled' => true,
                'expiry_minutes' => 5,
                'max_attempts' => 3,
            ],
            'registration' => [
                'enabled' => true,
                'expiry_minutes' => 10,
                'max_attempts' => 5,
            ],
            'reset' => [
                'enabled' => true,
                'expiry_minutes' => 15,
                'max_attempts' => 3,
            ],
            'verification' => [
                'enabled' => true,
                'expiry_minutes' => 10,
                'max_attempts' => 5,
            ],
        ],
        
        'security' => [
            'rate_limit_per_minute' => env('OTP_RATE_LIMIT_PER_MINUTE', 3),
            'rate_limit_per_hour' => env('OTP_RATE_LIMIT_PER_HOUR', 10),
            'ip_whitelist' => env('OTP_IP_WHITELIST', ''),
            'device_fingerprinting' => env('OTP_DEVICE_FINGERPRINTING', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Preferences Configuration
    |--------------------------------------------------------------------------
    */
    'preferences' => [
        'defaults' => [
            'sms' => [
                'transaction' => true,
                'security' => true,
                'marketing' => false,
                'announcement' => true,
            ],
            'email' => [
                'transaction' => true,
                'security' => true,
                'marketing' => false,
                'announcement' => true,
                'weekly_report' => true,
                'monthly_statement' => true,
            ],
            'push' => [
                'transaction' => true,
                'security' => true,
                'marketing' => false,
                'announcement' => true,
                'reminder' => true,
            ],
        ],
        
        'channels' => [
            'transaction' => ['sms', 'email', 'push'],
            'security' => ['sms', 'email', 'push'],
            'marketing' => ['email'],
            'announcement' => ['sms', 'email', 'push'],
            'reminder' => ['push'],
            'report' => ['email'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization Configuration
    |--------------------------------------------------------------------------
    */
    'localization' => [
        'default_language' => 'ar',
        'supported_languages' => ['ar', 'en'],
        
        'language_names' => [
            'ar' => 'العربية',
            'en' => 'English',
        ],
        
        'rtl_languages' => ['ar'],
        
        'date_formats' => [
            'ar' => 'd/m/Y',
            'en' => 'm/d/Y',
        ],
        
        'time_formats' => [
            'ar' => 'H:i',
            'en' => 'h:i A',
        ],
        
        'number_formats' => [
            'ar' => [
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'currency_symbol' => 'ر.س',
                'currency_position' => 'right',
            ],
            'en' => [
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'currency_symbol' => 'SAR',
                'currency_position' => 'left',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Delivery Configuration
    |--------------------------------------------------------------------------
    */
    'delivery' => [
        'retry_attempts' => env('COMMUNICATION_RETRY_ATTEMPTS', 3),
        'retry_delay_seconds' => env('COMMUNICATION_RETRY_DELAY_SECONDS', 60),
        'timeout_seconds' => env('COMMUNICATION_TIMEOUT_SECONDS', 30),
        
        'queues' => [
            'sms' => env('SMS_QUEUE', 'sms'),
            'email' => env('EMAIL_QUEUE', 'email'),
            'push' => env('PUSH_QUEUE', 'push'),
            'notification' => env('NOTIFICATION_QUEUE', 'notification'),
        ],
        
        'batch_size' => [
            'sms' => env('SMS_BATCH_SIZE', 100),
            'email' => env('EMAIL_BATCH_SIZE', 50),
            'push' => env('PUSH_BATCH_SIZE', 200),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring Configuration
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'enabled' => env('COMMUNICATION_MONITORING_ENABLED', true),
        'log_level' => env('COMMUNICATION_LOG_LEVEL', 'info'),
        
        'metrics' => [
            'delivery_rate' => true,
            'response_time' => true,
            'error_rate' => true,
            'cost_tracking' => true,
        ],
        
        'alerts' => [
            'delivery_failure_threshold' => env('COMMUNICATION_ALERT_THRESHOLD', 0.1),
            'response_time_threshold' => env('COMMUNICATION_RESPONSE_THRESHOLD', 30),
            'error_rate_threshold' => env('COMMUNICATION_ERROR_THRESHOLD', 0.05),
        ],
        
        'webhooks' => [
            'delivery_status' => env('COMMUNICATION_DELIVERY_WEBHOOK'),
            'error_reporting' => env('COMMUNICATION_ERROR_WEBHOOK'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Configuration
    |--------------------------------------------------------------------------
    */
    'testing' => [
        'enabled' => env('COMMUNICATION_TESTING_ENABLED', false),
        'test_phone' => env('COMMUNICATION_TEST_PHONE', '+966500000000'),
        'test_email' => env('COMMUNICATION_TEST_EMAIL', 'test@p-finance.com'),
        
        'mock_services' => [
            'sms' => env('MOCK_SMS_SERVICE', false),
            'email' => env('MOCK_EMAIL_SERVICE', false),
            'push' => env('MOCK_PUSH_SERVICE', false),
        ],
        
        'test_templates' => [
            'sms' => 'Test SMS message from P-Finance',
            'email' => 'Test email from P-Finance',
            'push' => 'Test push notification from P-Finance',
        ],
    ],
];
