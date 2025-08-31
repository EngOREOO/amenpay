# Laravel Backend Development Requirements for P-Finance App

## Project Overview
Create a comprehensive Laravel backend with admin dashboard and RESTful APIs for a Saudi Arabian digital wallet and payment application called "P-Finance" (محفظة رقمية آمنة). The app supports Arabic/English localization with RTL layout and follows Saudi financial regulations.

## Core Features to Implement

### 1. Authentication & User Management
- **Phone-based OTP authentication** (Saudi phone numbers +966)
- **User registration with ID verification** (Saudi National ID)
- **Profile management** (name, email, phone, avatar, language preference)
- **Multi-language support** (Arabic/English with RTL)
- **Security features** (2FA, session management, device tracking)

### 2. Wallet & Card Management
- **Digital wallet creation** with unique wallet IDs
- **Credit/Debit card management** (Visa, Mastercard, Mada)
- **Card scanning and validation**
- **Balance tracking** in Saudi Riyals (SAR)
- **Card security** (CVV, expiry validation, card type detection)

### 3. Transaction System
- **Payment processing** (in-app purchases, bill payments)
- **Money transfers** (peer-to-peer, bank transfers)
- **Transaction categories** (food, transport, shopping, entertainment, bills)
- **Transaction history** with detailed analytics
- **Receipt generation** and sharing

### 4. Payment Features
- **Bill payments** (electricity, water, gas, internet)
- **Transport payments** (fuel, insurance, maintenance)
- **Shopping categories** (clothing, electronics, furniture)
- **Entertainment payments** (cinema, restaurants, travel)
- **QR code payments** and NFC support

### 5. Analytics & Insights
- **Spending analytics** (daily, weekly, monthly, yearly)
- **Budget tracking** and goal setting
- **Financial insights** and recommendations
- **Expense categorization** and reports
- **Savings goals** tracking

### 6. Notifications & Communication
- **Push notifications** for transactions
- **SMS notifications** for security
- **Email notifications** for receipts
- **In-app messaging** system
- **Announcements** and updates

## Database Schema Requirements

### Core Tables

#### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NULL,
    name VARCHAR(255) NOT NULL,
    national_id VARCHAR(20) UNIQUE NULL,
    avatar VARCHAR(255) NULL,
    language ENUM('ar', 'en') DEFAULT 'ar',
    is_verified BOOLEAN DEFAULT FALSE,
    email_verified_at TIMESTAMP NULL,
    phone_verified_at TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
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
    wallet_number VARCHAR(50) UNIQUE NOT NULL,
    balance DECIMAL(15,2) DEFAULT 0.00,
    currency VARCHAR(3) DEFAULT 'SAR',
    status ENUM('active', 'suspended', 'closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Cards Table
```sql
CREATE TABLE cards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    card_number_hash VARCHAR(255) NOT NULL,
    card_type ENUM('visa', 'mastercard', 'mada') NOT NULL,
    expiry_date VARCHAR(5) NOT NULL,
    cardholder_name VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Transactions Table
```sql
CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id BIGINT UNSIGNED NOT NULL,
    type ENUM('payment', 'transfer', 'deposit', 'withdrawal', 'refund') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'SAR',
    category_id BIGINT UNSIGNED NULL,
    description TEXT NULL,
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    reference_id VARCHAR(100) UNIQUE NULL,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

#### Categories Table
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(255) NOT NULL,
    name_en VARCHAR(255) NOT NULL,
    icon VARCHAR(100) NULL,
    color VARCHAR(7) NULL,
    parent_id BIGINT UNSIGNED NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

#### Notifications Table
```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(50) NOT NULL,
    title_ar VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    message_ar TEXT NOT NULL,
    message_en TEXT NOT NULL,
    data JSON NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### OTP Codes Table
```sql
CREATE TABLE otp_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) NOT NULL,
    code VARCHAR(6) NOT NULL,
    type ENUM('registration', 'login', 'reset') NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### User Sessions Table
```sql
CREATE TABLE user_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    device_info JSON NULL,
    ip_address VARCHAR(45) NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## API Endpoints Structure

### Authentication APIs
```
POST /api/auth/register
POST /api/auth/login
POST /api/auth/verify-otp
POST /api/auth/resend-otp
POST /api/auth/logout
POST /api/auth/refresh-token
POST /api/auth/forgot-password
POST /api/auth/reset-password
```

### User Management APIs
```
GET /api/user/profile
PUT /api/user/profile
POST /api/user/upload-avatar
PUT /api/user/change-password
PUT /api/user/language
GET /api/user/security-settings
PUT /api/user/security-settings
GET /api/user/sessions
DELETE /api/user/sessions/{id}
```

### Wallet APIs
```
GET /api/wallet/balance
GET /api/wallet/transactions
POST /api/wallet/transfer
GET /api/wallet/analytics
GET /api/wallet/statement
POST /api/wallet/deposit
POST /api/wallet/withdrawal
```

### Card Management APIs
```
GET /api/cards
POST /api/cards
PUT /api/cards/{id}
DELETE /api/cards/{id}
POST /api/cards/validate
PUT /api/cards/{id}/default
```

### Payment APIs
```
POST /api/payments/process
GET /api/payments/categories
POST /api/payments/bill-payment
POST /api/payments/qr-payment
GET /api/payments/history
POST /api/payments/refund
GET /api/payments/status/{id}
```

### Analytics APIs
```
GET /api/analytics/spending
GET /api/analytics/income
GET /api/analytics/categories
GET /api/analytics/goals
POST /api/analytics/goals
PUT /api/analytics/goals/{id}
DELETE /api/analytics/goals/{id}
GET /api/analytics/reports
```

### Notification APIs
```
GET /api/notifications
PUT /api/notifications/{id}/read
PUT /api/notifications/read-all
DELETE /api/notifications/{id}
GET /api/notifications/settings
PUT /api/notifications/settings
```

## Admin Dashboard Requirements

### 1. User Management
- **User listing** with search and filters
- **User verification** management
- **User blocking/unblocking**
- **User activity monitoring**
- **Bulk operations** (export, mass actions)
- **User statistics** and analytics

### 2. Transaction Management
- **Transaction monitoring** in real-time
- **Transaction approval/rejection**
- **Fraud detection** and alerts
- **Transaction analytics** and reports
- **Refund management**
- **Transaction disputes** handling

### 3. Financial Management
- **System balance** monitoring
- **Revenue tracking** and analytics
- **Commission management**
- **Settlement reports**
- **Financial reconciliation**
- **Tax reporting**

### 4. System Administration
- **Category management** (CRUD operations)
- **Notification management**
- **System settings** configuration
- **API key management**
- **Log monitoring** and debugging
- **Backup management**

### 5. Analytics Dashboard
- **Real-time metrics** (users, transactions, revenue)
- **Interactive charts** and graphs
- **Performance indicators** (KPIs)
- **Geographic analytics**
- **Trend analysis**
- **Custom reports** generation

### 6. Security & Compliance
- **Fraud monitoring** dashboard
- **Compliance reporting**
- **Audit logs** viewer
- **Security alerts** management
- **KYC/AML** monitoring
- **Regulatory reporting**

## Technical Requirements

### Laravel Setup
- **Laravel 10+** with PHP 8.1+
- **MySQL 8.0+** or PostgreSQL 13+
- **Redis** for caching and sessions
- **Queue system** for background jobs
- **API authentication** with Sanctum
- **File storage** with AWS S3 or local

### Security Features
- **JWT tokens** for API authentication
- **Rate limiting** and throttling
- **Input validation** and sanitization
- **SQL injection** prevention
- **XSS protection**
- **CSRF protection**
- **Encryption** for sensitive data
- **Two-factor authentication** (2FA)

### Payment Integration
- **Saudi payment gateways** (STC Pay, mada, Apple Pay)
- **Bank integration** APIs
- **QR code generation** and scanning
- **Payment webhooks** handling
- **Transaction reconciliation**
- **Multi-currency support**

### Localization
- **Arabic/English** language support
- **RTL layout** handling
- **Date/time** formatting (Hijri calendar support)
- **Currency formatting** (SAR)
- **Number formatting** (Arabic numerals)
- **Cultural adaptations**

### Performance & Scalability
- **Database optimization** and indexing
- **Caching strategies** (Redis, Memcached)
- **API response** optimization
- **Image optimization** and CDN
- **Load balancing** ready
- **Microservices** architecture consideration
- **Horizontal scaling** support

## Additional Features

### 1. Compliance & Regulations
- **SAMA compliance** (Saudi Arabian Monetary Authority)
- **KYC/AML** procedures
- **Data protection** (GDPR equivalent)
- **Audit logging** and trails
- **Regulatory reporting**
- **Data retention** policies

### 2. Integration Capabilities
- **SMS gateway** integration (Twilio, etc.)
- **Email service** integration
- **Push notification** service
- **Social media** login (Google, Apple)
- **Third-party** payment processors
- **Banking APIs** integration

### 3. Monitoring & Maintenance
- **Error tracking** and logging
- **Performance monitoring**
- **Health checks** and alerts
- **Backup and recovery** procedures
- **Deployment automation**
- **Uptime monitoring**

### 4. Advanced Features
- **AI-powered fraud detection**
- **Machine learning** for spending insights
- **Predictive analytics**
- **Automated customer support**
- **Multi-language customer support**
- **Advanced reporting** tools

## Development Guidelines

### Code Quality
- **PSR-12** coding standards
- **Laravel best practices**
- **Comprehensive testing** (Unit, Feature, Integration)
- **API documentation** with OpenAPI/Swagger
- **Code review** process
- **Static analysis** tools

### Database Design
- **Normalized schema** design
- **Proper indexing** strategy
- **Migration files** for version control
- **Seeders** for test data
- **Backup strategies**
- **Performance optimization**

### API Design
- **RESTful principles**
- **Consistent response** format
- **Error handling** standards
- **Versioning** strategy
- **Rate limiting** implementation
- **API documentation**

### Frontend Integration
- **CORS configuration** for Flutter app
- **Real-time updates** with WebSockets
- **File upload** handling
- **Progressive Web App** support
- **Offline capability** considerations
- **Mobile-first** design

## Deployment Requirements

### Environment Setup
- **Production server** configuration
- **SSL certificates** and HTTPS
- **Domain configuration**
- **Environment variables** management
- **Database backup** automation
- **CDN setup**

### DevOps
- **CI/CD pipeline** setup
- **Docker containerization**
- **Load balancer** configuration
- **Monitoring tools** integration
- **Log aggregation** system
- **Auto-scaling** configuration

### Security Deployment
- **Firewall** configuration
- **DDoS protection**
- **SSL/TLS** implementation
- **Security headers** setup
- **Vulnerability scanning**
- **Penetration testing**

## Testing Strategy

### Unit Testing
- **Model testing**
- **Service testing**
- **Helper function testing**
- **Validation testing**

### Feature Testing
- **API endpoint testing**
- **Authentication testing**
- **Payment flow testing**
- **User workflow testing**

### Integration Testing
- **Third-party service testing**
- **Database integration testing**
- **Payment gateway testing**
- **SMS/Email service testing**

### Performance Testing
- **Load testing**
- **Stress testing**
- **Database performance testing**
- **API response time testing**

## Documentation Requirements

### API Documentation
- **OpenAPI/Swagger** specification
- **Endpoint descriptions**
- **Request/Response examples**
- **Error codes** documentation
- **Authentication** guide

### Technical Documentation
- **System architecture** documentation
- **Database schema** documentation
- **Deployment guide**
- **Troubleshooting guide**
- **Security guidelines**

### User Documentation
- **Admin dashboard** user guide
- **API integration** guide
- **Payment flow** documentation
- **Compliance** documentation

## Compliance & Legal

### Saudi Regulations
- **SAMA compliance** requirements
- **E-commerce regulations**
- **Data protection laws**
- **Financial services regulations**
- **Anti-money laundering** (AML) compliance

### International Standards
- **PCI DSS** compliance for payment processing
- **ISO 27001** information security
- **GDPR** equivalent data protection
- **SOC 2** compliance
- **Financial audit** requirements

## Support & Maintenance

### Technical Support
- **24/7 monitoring** and alerting
- **Incident response** procedures
- **Bug tracking** and resolution
- **Performance optimization**
- **Security updates**

### Customer Support
- **Multi-language support** (Arabic/English)
- **Ticket management** system
- **Knowledge base** creation
- **FAQ management**
- **User training** materials

---

This comprehensive Laravel backend should provide a robust, scalable, and secure foundation for the P-Finance mobile application, supporting all the features identified in the Flutter codebase while maintaining compliance with Saudi financial regulations and providing an excellent user experience for both Arabic and English users.
