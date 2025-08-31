# P-Finance Backend Documentation

## 📚 Table of Contents

1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [Installation & Setup](#installation--setup)
4. [Database Schema](#database-schema)
5. [API Endpoints](#api-endpoints)
6. [Authentication & Security](#authentication--security)
7. [Models & Relationships](#models--relationships)
8. [Testing](#testing)
9. [Deployment](#deployment)
10. [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

P-Finance is a comprehensive financial management backend system built with Laravel 12, designed specifically for the Saudi Arabian market. The system provides complete financial services including wallet management, payment processing, budgeting, goal tracking, and advanced analytics.

### Key Features
- **Multi-language Support**: Arabic and English with RTL layout support
- **Saudi Payment Gateways**: STC Pay, mada, Apple Pay integration
- **Advanced Security**: OTP authentication, role-based access control
- **Comprehensive Analytics**: AI-powered insights and spending patterns
- **Real-time Notifications**: Push, SMS, email, and in-app messaging
- **Admin Dashboard**: Complete backend management system

---

## 🏗️ System Architecture

### Technology Stack
- **Framework**: Laravel 12 (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **Cache**: Redis (optional)
- **Queue**: Laravel Queue with database driver
- **Testing**: PHPUnit with coverage reporting

### Architecture Patterns
- **MVC Pattern**: Model-View-Controller architecture
- **Repository Pattern**: Data access abstraction
- **Service Layer**: Business logic separation
- **Middleware**: Request/response filtering
- **API Resources**: Response transformation

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer 2.0+
- MySQL 8.0+
- Node.js 18+ (for frontend assets)

### Quick Start
```bash
# Clone repository
git clone <repository-url>
cd p-finance-backend

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database configuration
# Update .env with your MySQL credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=p_finance
DB_USERNAME=amen
DB_PASSWORD=

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

### Environment Variables
```env
# Application
APP_NAME="P-Finance"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=p_finance
DB_USERNAME=amen
DB_PASSWORD=

# Payment Gateways
STC_PAY_MERCHANT_ID=your_merchant_id
STC_PAY_API_KEY=your_api_key
STC_PAY_SECRET_KEY=your_secret_key

MADA_MERCHANT_ID=your_merchant_id
MADA_TERMINAL_ID=your_terminal_id
MADA_SECRET_KEY=your_secret_key

# SMS Gateway (Twilio)
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_number

# Push Notifications
FCM_SERVER_KEY=your_fcm_server_key
APNS_CERTIFICATE_PATH=path_to_certificate
APNS_PRIVATE_KEY_PATH=path_to_private_key
```

---

## 🗄️ Database Schema

### Core Tables

#### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NULLABLE,
    name VARCHAR(255) NOT NULL,
    national_id VARCHAR(20) UNIQUE NULLABLE,
    avatar VARCHAR(255) NULLABLE,
    language ENUM('ar', 'en') DEFAULT 'ar',
    is_verified BOOLEAN DEFAULT FALSE,
    email_verified_at TIMESTAMP NULLABLE,
    phone_verified_at TIMESTAMP NULLABLE,
    last_login_at TIMESTAMP NULLABLE,
    status ENUM('active', 'suspended', 'blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Wallets Table
```sql
CREATE TABLE wallets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    wallet_id VARCHAR(50) UNIQUE NOT NULL,
    balance DECIMAL(15,2) DEFAULT 0.00,
    currency VARCHAR(3) DEFAULT 'SAR',
    status ENUM('active', 'suspended', 'closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Payment Gateways Table
```sql
CREATE TABLE payment_gateways (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('saudi_gateway', 'international', 'bank_api', 'mobile_wallet') DEFAULT 'saudi_gateway',
    status ENUM('active', 'inactive', 'maintenance', 'deprecated') DEFAULT 'active',
    configuration JSON NULLABLE,
    supported_currencies JSON DEFAULT '["SAR"]',
    supported_payment_methods JSON NULLABLE,
    transaction_fee_percentage DECIMAL(5,4) DEFAULT 0.0000,
    transaction_fee_fixed DECIMAL(10,2) DEFAULT 0.00,
    min_transaction_amount INT DEFAULT 1,
    max_transaction_amount INT DEFAULT 50000,
    environment ENUM('sandbox', 'production') DEFAULT 'sandbox',
    health_status ENUM('healthy', 'warning', 'error', 'unknown') DEFAULT 'unknown',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Complete Schema Documentation
For the complete database schema, see [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md)

---

## 🔌 API Endpoints

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication Endpoints

#### Public Routes
```http
POST /auth/register
POST /auth/verify-otp
POST /auth/login
POST /auth/login-with-otp
POST /auth/resend-otp
POST /auth/forgot-password
POST /auth/reset-password
```

#### Protected Routes
```http
POST /auth/logout
POST /auth/refresh-token
PUT /auth/change-password
```

### User Management
```http
GET    /user/profile
PUT    /user/profile
POST   /user/upload-avatar
PUT    /user/language
GET    /user/security-settings
PUT    /user/security-settings
GET    /user/sessions
DELETE /user/sessions/{id}
```

### Wallet Operations
```http
GET  /wallet/balance
GET  /wallet/transactions
POST /wallet/transfer
GET  /wallet/analytics
GET  /wallet/statement
POST /wallet/deposit
POST /wallet/withdrawal
```

### Payment Processing
```http
POST /payments/process
GET  /payments/categories
POST /payments/bill-payment
POST /payments/qr-payment
GET  /payments/history
```

### Complete API Documentation
For complete API documentation with request/response examples, see [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)

---

## 🔐 Authentication & Security

### Authentication Flow
1. **User Registration**: Phone number + OTP verification
2. **Login**: Phone + OTP or Phone + Password
3. **Token Management**: Bearer token with refresh capability
4. **Session Tracking**: Device information and login history

### Security Features
- **OTP Verification**: SMS-based one-time password
- **Token Expiration**: Configurable token lifetime
- **Rate Limiting**: API request throttling
- **Input Validation**: Comprehensive request validation
- **SQL Injection Protection**: Eloquent ORM with prepared statements
- **XSS Protection**: Output escaping and sanitization

### Middleware
- **api.auth**: API authentication middleware
- **admin.auth**: Admin-only access middleware
- **throttle**: Rate limiting middleware
- **cors**: Cross-origin resource sharing

---

## 🎭 Models & Relationships

### Core Models

#### User Model
```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTranslations;

    protected $fillable = [
        'phone', 'email', 'name', 'national_id', 'avatar',
        'language', 'is_verified', 'status'
    ];

    // Relationships
    public function wallet() { return $this->hasOne(Wallet::class); }
    public function cards() { return $this->hasMany(Card::class); }
    public function transactions() { return $this->hasManyThrough(Transaction::class, Wallet::class); }
    public function budgets() { return $this->hasMany(Budget::class); }
    public function goals() { return $this->hasMany(Goal::class); }
}
```

#### Wallet Model
```php
class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'balance', 'currency', 'status'
    ];

    // Business Logic Methods
    public function addBalance($amount, $description = null) { /* ... */ }
    public function deductBalance($amount, $description = null) { /* ... */ }
    public function transferTo($targetWallet, $amount, $description = null) { /* ... */ }
}
```

### Complete Model Documentation
For complete model documentation with all methods and relationships, see [MODELS_DOCUMENTATION.md](./MODELS_DOCUMENTATION.md)

---

## 🧪 Testing

### Test Structure
```
tests/
├── Feature/           # Feature tests
│   ├── UserTest.php
│   ├── PaymentGatewayTest.php
│   └── NotificationSystemTest.php
├── Unit/             # Unit tests
├── Integration/      # Integration tests
└── TestCase.php      # Base test class
```

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage-html=coverage/html

# Run specific test
php artisan test --filter=test_user_can_be_created

# Run tests with database
php artisan test --database
```

### Test Coverage
- **Unit Tests**: Individual component testing
- **Feature Tests**: End-to-end functionality testing
- **Integration Tests**: Component interaction testing
- **Performance Tests**: Load and stress testing

### Test Configuration
```xml
<!-- phpunit.xml -->
<coverage>
    <report>
        <html outputDirectory="coverage/html"/>
        <text outputFile="coverage/coverage.txt"/>
        <clover outputFile="coverage/clover.xml"/>
    </report>
</coverage>
```

---

## 🚀 Deployment

### Production Requirements
- **Server**: Ubuntu 20.04+ / CentOS 8+
- **PHP**: 8.1+ with required extensions
- **Database**: MySQL 8.0+ with proper configuration
- **Web Server**: Nginx or Apache
- **SSL Certificate**: Valid SSL certificate for HTTPS
- **Redis**: For caching and session storage (recommended)

### Deployment Steps
```bash
# 1. Server preparation
sudo apt update && sudo apt upgrade -y
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql

# 2. Application deployment
cd /var/www
sudo git clone <repository-url> p-finance
sudo chown -R www-data:www-data p-finance
cd p-finance

# 3. Environment configuration
sudo cp .env.example .env
sudo nano .env  # Configure production settings

# 4. Dependencies and optimization
sudo composer install --optimize-autoloader --no-dev
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache

# 5. Database setup
sudo php artisan migrate --force
sudo php artisan db:seed --force

# 6. Web server configuration
sudo nano /etc/nginx/sites-available/p-finance
sudo ln -s /etc/nginx/sites-available/p-finance /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### Environment Configuration
```env
# Production settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=p_finance_prod
DB_USERNAME=production_user
DB_PASSWORD=strong_password

# Cache and Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
```

---

## 🔧 Troubleshooting

### Common Issues

#### Database Connection Issues
```bash
# Check MySQL service
sudo systemctl status mysql

# Test connection
mysql -u amen -p

# Check permissions
GRANT ALL PRIVILEGES ON p_finance.* TO 'amen'@'localhost';
FLUSH PRIVILEGES;
```

#### Migration Issues
```bash
# Reset database
php artisan migrate:reset

# Fresh migration with seeders
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

#### Permission Issues
```bash
# Fix storage permissions
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/

# Fix ownership
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
```

#### Performance Issues
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize autoloader
composer dump-autoload --optimize

# Check logs
tail -f storage/logs/laravel.log
```

### Log Files
- **Application Logs**: `storage/logs/laravel.log`
- **Error Logs**: `/var/log/nginx/error.log`
- **Access Logs**: `/var/log/nginx/access.log`
- **MySQL Logs**: `/var/log/mysql/error.log`

### Support
For additional support and troubleshooting:
- **Documentation**: Check this documentation first
- **Issues**: Create GitHub issue with detailed description
- **Community**: Join our developer community
- **Email**: support@p-finance.com

---

## 📚 Additional Resources

### Documentation Files
- [API Documentation](./API_DOCUMENTATION.md)
- [Database Schema](./DATABASE_SCHEMA.md)
- [Models Documentation](./MODELS_DOCUMENTATION.md)
- [Testing Guide](./TESTING_GUIDE.md)
- [Deployment Guide](./DEPLOYMENT_GUIDE.md)

### External Resources
- [Laravel Documentation](https://laravel.com/docs)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PHP Documentation](https://www.php.net/docs.php)
- [Saudi Payment Standards](https://www.sama.gov.sa/)

### Version Information
- **Laravel Version**: 12.x
- **PHP Version**: 8.1+
- **MySQL Version**: 8.0+
- **Last Updated**: August 2025

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details on how to submit pull requests, report issues, and contribute to the project.

---

*This documentation is maintained by the P-Finance development team. For questions or suggestions, please contact us.*
