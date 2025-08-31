<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for various payment gateways
    | used in the P-Finance application, including Saudi-specific gateways.
    |
    */

    'default_gateway' => env('PAYMENT_DEFAULT_GATEWAY', 'mada'),

    /*
    |--------------------------------------------------------------------------
    | STC Pay Configuration
    |--------------------------------------------------------------------------
    */
    'stc_pay' => [
        'enabled' => env('STC_PAY_ENABLED', false),
        'merchant_id' => env('STC_PAY_MERCHANT_ID'),
        'api_key' => env('STC_PAY_API_KEY'),
        'secret_key' => env('STC_PAY_SECRET_KEY'),
        'api_url' => env('STC_PAY_API_URL', 'https://api.stcpay.com.sa/v1'),
        'webhook_secret' => env('STC_PAY_WEBHOOK_SECRET'),
        'sandbox' => env('STC_PAY_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mada Configuration
    |--------------------------------------------------------------------------
    */
    'mada' => [
        'enabled' => env('MADA_ENABLED', true),
        'merchant_id' => env('MADA_MERCHANT_ID'),
        'api_key' => env('MADA_API_KEY'),
        'secret_key' => env('MADA_SECRET_KEY'),
        'api_url' => env('MADA_API_URL', 'https://api.mada.com.sa/v1'),
        'webhook_secret' => env('MADA_WEBHOOK_SECRET'),
        'sandbox' => env('MADA_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Apple Pay Configuration
    |--------------------------------------------------------------------------
    */
    'apple_pay' => [
        'enabled' => env('APPLE_PAY_ENABLED', false),
        'merchant_id' => env('APPLE_PAY_MERCHANT_ID'),
        'api_key' => env('APPLE_PAY_API_KEY'),
        'secret_key' => env('APPLE_PAY_SECRET_KEY'),
        'api_url' => env('APPLE_PAY_API_URL', 'https://api.applepay.com/v1'),
        'webhook_secret' => env('APPLE_PAY_WEBHOOK_SECRET'),
        'sandbox' => env('APPLE_PAY_SANDBOX', true),
        'merchant_identifier' => env('APPLE_PAY_MERCHANT_IDENTIFIER'),
        'domain_verification' => env('APPLE_PAY_DOMAIN_VERIFICATION'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bank Transfer Configuration
    |--------------------------------------------------------------------------
    */
    'bank_transfer' => [
        'enabled' => env('BANK_TRANSFER_ENABLED', true),
        'bank_name' => env('BANK_NAME', 'البنك السعودي الفرنسي'),
        'account_number' => env('BANK_ACCOUNT_NUMBER'),
        'iban' => env('BANK_IBAN'),
        'swift_code' => env('BANK_SWIFT_CODE'),
        'beneficiary_name' => env('BANK_BENEFICIARY_NAME', 'P-Finance'),
        'transfer_expiry_days' => env('BANK_TRANSFER_EXPIRY_DAYS', 7),
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Configuration
    |--------------------------------------------------------------------------
    */
    'qr_code' => [
        'enabled' => env('QR_CODE_ENABLED', true),
        'size' => env('QR_CODE_SIZE', 300),
        'margin' => env('QR_CODE_MARGIN', 10),
        'format' => env('QR_CODE_FORMAT', 'png'),
        'expiry_minutes' => env('QR_CODE_EXPIRY_MINUTES', 15),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Processing Configuration
    |--------------------------------------------------------------------------
    */
    'processing' => [
        'timeout_seconds' => env('PAYMENT_TIMEOUT_SECONDS', 300),
        'max_retries' => env('PAYMENT_MAX_RETRIES', 3),
        'retry_delay_seconds' => env('PAYMENT_RETRY_DELAY_SECONDS', 60),
        'auto_refund_failed' => env('PAYMENT_AUTO_REFUND_FAILED', true),
        'webhook_timeout' => env('PAYMENT_WEBHOOK_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'default' => 'SAR',
        'supported' => ['SAR', 'USD', 'EUR'],
        'exchange_rates' => [
            'USD' => 3.75,
            'EUR' => 4.10,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Commission & Fees Configuration
    |--------------------------------------------------------------------------
    */
    'fees' => [
        'transaction_fee_percentage' => env('TRANSACTION_FEE_PERCENTAGE', 0.5),
        'transaction_fee_minimum' => env('TRANSACTION_FEE_MINIMUM', 1.00),
        'transaction_fee_maximum' => env('TRANSACTION_FEE_MAXIMUM', 50.00),
        'withdrawal_fee_percentage' => env('WITHDRAWAL_FEE_PERCENTAGE', 1.0),
        'withdrawal_fee_minimum' => env('WITHDRAWAL_FEE_MINIMUM', 5.00),
        'withdrawal_fee_maximum' => env('WITHDRAWAL_FEE_MAXIMUM', 100.00),
    ],

    /*
    |--------------------------------------------------------------------------
    | Limits Configuration
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'daily_transaction_limit' => env('DAILY_TRANSACTION_LIMIT', 10000),
        'monthly_transaction_limit' => env('MONTHLY_TRANSACTION_LIMIT', 100000),
        'single_transaction_limit' => env('SINGLE_TRANSACTION_LIMIT', 5000),
        'daily_withdrawal_limit' => env('DAILY_WITHDRAWAL_LIMIT', 5000),
        'monthly_withdrawal_limit' => env('MONTHLY_WITHDRAWAL_LIMIT', 50000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'signature_algorithm' => 'sha256',
        'webhook_verification' => env('PAYMENT_WEBHOOK_VERIFICATION', true),
        'ip_whitelist' => env('PAYMENT_IP_WHITELIST', ''),
        'rate_limit_per_minute' => env('PAYMENT_RATE_LIMIT_PER_MINUTE', 60),
        'fraud_detection_enabled' => env('PAYMENT_FRAUD_DETECTION_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'email_enabled' => env('PAYMENT_EMAIL_NOTIFICATIONS', true),
        'sms_enabled' => env('PAYMENT_SMS_NOTIFICATIONS', true),
        'push_enabled' => env('PAYMENT_PUSH_NOTIFICATIONS', true),
        'admin_notifications' => env('PAYMENT_ADMIN_NOTIFICATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('PAYMENT_LOGGING_ENABLED', true),
        'level' => env('PAYMENT_LOGGING_LEVEL', 'info'),
        'log_failed_transactions' => env('PAYMENT_LOG_FAILED_TRANSACTIONS', true),
        'log_successful_transactions' => env('PAYMENT_LOG_SUCCESSFUL_TRANSACTIONS', true),
        'log_webhooks' => env('PAYMENT_LOG_WEBHOOKS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Configuration
    |--------------------------------------------------------------------------
    */
    'testing' => [
        'enabled' => env('PAYMENT_TESTING_ENABLED', false),
        'test_cards' => [
            'mada' => [
                'success' => '4462030000000000',
                'declined' => '4462030000000001',
                'insufficient_funds' => '4462030000000002',
            ],
            'visa' => [
                'success' => '4111111111111111',
                'declined' => '4000000000000002',
                'insufficient_funds' => '4000000000009995',
            ],
        ],
        'test_amounts' => [
            'min' => 1.00,
            'max' => 1000.00,
        ],
    ],
];
