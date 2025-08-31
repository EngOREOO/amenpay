# P-Finance API Documentation

## Overview

The P-Finance API provides a comprehensive set of endpoints for managing digital wallets, payments, transactions, and financial services. The API follows RESTful principles and supports both Arabic and English localization.

**Base URL**: `https://api.p-finance.com/v1`  
**API Version**: 1.0.0  
**Authentication**: Bearer Token (Laravel Sanctum)  
**Rate Limiting**: Yes (configurable per endpoint)  
**Localization**: Arabic (ar) and English (en)  

## Authentication

### Getting an Access Token

```http
POST /api/auth/login
```

**Request Body:**
```json
{
    "phone": "+966500000000",
    "otp": "123456"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "phone": "+966500000000",
            "email": "ahmed@p-finance.com",
            "language": "ar",
            "is_verified": true
        },
        "token": "1|abc123...",
        "token_type": "Bearer",
        "expires_in": 3600
    }
}
```

### Using the Access Token

Include the token in the Authorization header:

```http
Authorization: Bearer 1|abc123...
```

## User Management

### Get User Profile

```http
GET /api/user/profile
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "أحمد محمد",
        "phone": "+966500000000",
        "email": "ahmed@p-finance.com",
        "national_id": "1234567890",
        "avatar": "https://cdn.p-finance.com/avatars/user_1.jpg",
        "language": "ar",
        "is_verified": true,
        "status": "active",
        "created_at": "2024-01-15T10:30:00Z"
    }
}
```

### Update User Profile

```http
PUT /api/user/profile
```

**Request Body:**
```json
{
    "name": "أحمد محمد علي",
    "email": "ahmed.ali@p-finance.com",
    "language": "en"
}
```

### Upload Avatar

```http
POST /api/user/upload-avatar
Content-Type: multipart/form-data
```

**Request Body:**
```
avatar: [image file]
```

## Wallet Management

### Get Wallet Balance

```http
GET /api/wallet/balance
```

**Response:**
```json
{
    "success": true,
    "data": {
        "wallet_number": "WAL123456789",
        "balance": 1500.50,
        "currency": "SAR",
        "status": "active",
        "last_updated": "2024-01-15T10:30:00Z"
    }
}
```

### Get Wallet Transactions

```http
GET /api/wallet/transactions?page=1&per_page=20&type=all
```

**Query Parameters:**
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 20, max: 100)
- `type`: Transaction type (all, payment, transfer, deposit, withdrawal)
- `status`: Transaction status (all, pending, completed, failed)
- `start_date`: Start date (YYYY-MM-DD)
- `end_date`: End date (YYYY-MM-DD)

**Response:**
```json
{
    "success": true,
    "data": {
        "transactions": [
            {
                "id": 1,
                "type": "payment",
                "amount": 100.00,
                "currency": "SAR",
                "description": "Payment for services",
                "status": "completed",
                "reference_id": "TXN123456",
                "created_at": "2024-01-15T10:30:00Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 150,
            "last_page": 8
        }
    }
}
```

### Transfer Money

```http
POST /api/wallet/transfer
```

**Request Body:**
```json
{
    "recipient_phone": "+966500000001",
    "amount": 200.00,
    "description": "Transfer to friend",
    "pin": "1234"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Transfer initiated successfully",
    "data": {
        "transaction_id": 2,
        "reference_id": "TXN789012",
        "amount": 200.00,
        "fee": 1.00,
        "net_amount": 199.00,
        "status": "pending"
    }
}
```

## Payment Processing

### Process Payment

```http
POST /api/payments/process
```

**Request Body:**
```json
{
    "amount": 500.00,
    "gateway": "mada",
    "card_token": "card_123456",
    "description": "Online purchase",
    "metadata": {
        "merchant_id": "MERCH123",
        "order_id": "ORDER456"
    }
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment processed successfully",
    "data": {
        "transaction_id": 3,
        "reference_id": "TXN345678",
        "amount": 500.00,
        "fee": 2.50,
        "net_amount": 497.50,
        "status": "completed",
        "gateway_transaction_id": "GTW789012"
    }
}
```

### Get Payment Categories

```http
GET /api/payments/categories
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name_ar": "الطعام والمطاعم",
            "name_en": "Food & Dining",
            "icon": "restaurant",
            "color": "#FF6B6B",
            "subcategories": [
                {
                    "id": 2,
                    "name_ar": "مطاعم",
                    "name_en": "Restaurants",
                    "icon": "restaurant",
                    "color": "#FF8E8E"
                }
            ]
        }
    ]
}
```

## Card Management

### Get User Cards

```http
GET /api/cards
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "card_type": "visa",
            "last_four": "1234",
            "expiry_date": "12/25",
            "cardholder_name": "أحمد محمد",
            "is_default": true,
            "is_active": true,
            "created_at": "2024-01-15T10:30:00Z"
        }
    ]
}
```

### Add New Card

```http
POST /api/cards
```

**Request Body:**
```json
{
    "card_number": "4111111111111111",
    "expiry_month": "12",
    "expiry_year": "2025",
    "cvv": "123",
    "cardholder_name": "أحمد محمد",
    "is_default": false
}
```

### Validate Card

```http
POST /api/cards/validate
```

**Request Body:**
```json
{
    "card_number": "4111111111111111",
    "expiry_month": "12",
    "expiry_year": "2025",
    "cvv": "123"
}
```

## Analytics & Insights

### Get Spending Analytics

```http
GET /api/analytics/spending?period=month&start_date=2024-01-01&end_date=2024-01-31
```

**Query Parameters:**
- `period`: Time period (day, week, month, year)
- `start_date`: Start date (YYYY-MM-DD)
- `end_date`: End date (YYYY-MM-DD)
- `category_id`: Filter by category

**Response:**
```json
{
    "success": true,
    "data": {
        "total_spending": 2500.00,
        "average_daily": 80.65,
        "top_categories": [
            {
                "category_id": 1,
                "category_name_ar": "الطعام والمطاعم",
                "category_name_en": "Food & Dining",
                "amount": 800.00,
                "percentage": 32.0
            }
        ],
        "spending_trend": [
            {
                "date": "2024-01-01",
                "amount": 75.00
            }
        ]
    }
}
```

### Get Financial Insights

```http
GET /api/analytics/insights
```

**Response:**
```json
{
    "success": true,
    "data": {
        "spending_patterns": [
            {
                "type": "increased_spending",
                "message_ar": "إنفاقك هذا الأسبوع زاد بنسبة 15% مقارنة بالأسبوع الماضي",
                "message_en": "Your spending this week increased by 15% compared to last week",
                "severity": "warning",
                "category": "food"
            }
        ],
        "savings_opportunities": [
            {
                "type": "reduce_food_spending",
                "message_ar": "يمكنك توفير 200 ريال شهرياً بتقليل إنفاقك على الطعام",
                "message_en": "You can save 200 SAR monthly by reducing food spending",
                "potential_savings": 200.00
            }
        ]
    }
}
```

## Budget Management

### Get Budgets

```http
GET /api/budgets
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name_ar": "ميزانية الطعام",
            "name_en": "Food Budget",
            "category_id": 1,
            "amount": 1000.00,
            "spent": 750.00,
            "remaining": 250.00,
            "period": "monthly",
            "start_date": "2024-01-01",
            "end_date": "2024-01-31",
            "status": "active"
        }
    ]
}
```

### Create Budget

```http
POST /api/budgets
```

**Request Body:**
```json
{
    "name_ar": "ميزانية النقل",
    "name_en": "Transport Budget",
    "category_id": 5,
    "amount": 500.00,
    "period": "monthly",
    "start_date": "2024-02-01",
    "end_date": "2024-02-29"
}
```

## Goals Management

### Get Financial Goals

```http
GET /api/goals
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name_ar": "شراء سيارة",
            "name_en": "Buy a Car",
            "target_amount": 50000.00,
            "current_amount": 15000.00,
            "remaining_amount": 35000.00,
            "target_date": "2025-12-31",
            "status": "active",
            "progress_percentage": 30.0
        }
    ]
}
```

### Add Amount to Goal

```http
POST /api/goals/1/add-amount
```

**Request Body:**
```json
{
    "amount": 1000.00,
    "description": "Monthly savings"
}
```

## Notifications

### Get Notifications

```http
GET /api/notifications?page=1&per_page=20&unread_only=false
```

**Query Parameters:**
- `page`: Page number
- `per_page`: Items per page
- `unread_only`: Show only unread notifications

**Response:**
```json
{
    "success": true,
    "data": {
        "notifications": [
            {
                "id": 1,
                "type": "transaction",
                "title_ar": "تم استلام الدفع",
                "title_en": "Payment Received",
                "message_ar": "تم استلام مبلغ 100 ريال في محفظتك",
                "message_en": "100 SAR received in your wallet",
                "is_read": false,
                "created_at": "2024-01-15T10:30:00Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 45,
            "last_page": 3
        }
    }
}
```

### Mark Notification as Read

```http
PUT /api/notifications/1/read
```

## Error Handling

The API uses standard HTTP status codes and returns error responses in the following format:

```json
{
    "success": false,
    "message": "Validation failed",
    "error": "validation_error",
    "errors": {
        "phone": ["The phone field is required."],
        "amount": ["The amount must be at least 1."]
    }
}
```

### Common Error Codes

- `400` - Bad Request (validation errors, invalid data)
- `401` - Unauthorized (invalid or missing token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found (resource doesn't exist)
- `422` - Unprocessable Entity (validation failed)
- `429` - Too Many Requests (rate limit exceeded)
- `500` - Internal Server Error

## Rate Limiting

The API implements rate limiting to prevent abuse:

- **Authentication endpoints**: 5 requests per 15 minutes
- **OTP endpoints**: 3 requests per 5 minutes
- **Payment endpoints**: 10 requests per minute
- **General API**: 60 requests per minute (authenticated users)
- **File uploads**: 20 requests per minute

Rate limit headers are included in responses:
- `X-RateLimit-Limit`: Maximum requests allowed
- `X-RateLimit-Remaining`: Remaining requests
- `X-RateLimit-Reset`: Time when limit resets

## Localization

The API supports Arabic and English localization:

- Set `Accept-Language` header: `ar` or `en`
- Or include `language` parameter in requests
- Responses include localized content based on user preference

## Webhooks

The API supports webhooks for real-time notifications:

### Webhook Endpoint

```http
POST /webhooks/payment
```

### Webhook Payload

```json
{
    "event": "payment.completed",
    "data": {
        "transaction_id": 123,
        "amount": 100.00,
        "status": "completed",
        "timestamp": "2024-01-15T10:30:00Z"
    },
    "signature": "webhook_signature_hash"
}
```

### Webhook Events

- `payment.completed` - Payment successfully completed
- `payment.failed` - Payment failed
- `transfer.completed` - Money transfer completed
- `user.verified` - User verification completed

## SDKs and Libraries

Official SDKs are available for:

- **JavaScript/Node.js**: `npm install p-finance-sdk`
- **PHP**: `composer require p-finance/p-finance-php`
- **Python**: `pip install p-finance-python`
- **Flutter/Dart**: Available in pub.dev

## Support

For API support and questions:

- **Email**: api-support@p-finance.com
- **Documentation**: https://docs.p-finance.com
- **Status Page**: https://status.p-finance.com
- **Developer Portal**: https://developers.p-finance.com

## Changelog

### Version 1.0.0 (2024-01-15)
- Initial API release
- Core wallet and payment functionality
- User management and authentication
- Analytics and reporting
- Multi-language support
