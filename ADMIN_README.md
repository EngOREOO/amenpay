# Admin Dashboard - Amen Pay

This document explains how to use the admin dashboard system for Amen Pay.

## 🚀 **Quick Start**

### **Default Admin Credentials:**
- **Email**: `admin@mail.com`
- **Password**: `password`

### **Access URLs:**
- **Admin Login**: http://localhost:8000/admin/login
- **Admin Dashboard**: http://localhost:8000/admin/dashboard
- **Admin Profile**: http://localhost:8000/admin/profile

## 🔐 **Authentication System**

### **Features:**
- Email-based authentication (no phone numbers)
- Session-based login with remember me
- Secure password hashing
- Role-based access control
- Permission system

### **Available Roles:**
1. **Super Admin** - Full access to all features
2. **Admin** - Limited administrative access
3. **Moderator** - Read-only access to most features

## 👥 **Default Admin Users**

### **1. Super Admin**
- **Email**: `admin@mail.com`
- **Password**: `password`
- **Role**: `super_admin`
- **Permissions**: All permissions (`*`)

### **2. Admin User**
- **Email**: `admin.user@mail.com`
- **Password**: `password`
- **Role**: `admin`
- **Permissions**: Users, transactions, reports

### **3. Moderator**
- **Email**: `moderator@mail.com`
- **Password**: `password`
- **Role**: `moderator`
- **Permissions**: View-only access

## 🛠 **Setup Instructions**

### **1. Run Migrations:**
```bash
php artisan migrate
```

### **2. Seed Admin Users:**
```bash
php artisan db:seed --class=AdminSeeder
```

### **3. Build CSS:**
```bash
npm run build
```

### **4. Start Server:**
```bash
php artisan serve
```

## 📱 **Admin Dashboard Features**

### **Main Dashboard:**
- System overview and statistics
- Recent activity monitoring
- Quick access to key functions
- User and transaction counts

### **User Management:**
- View all registered users
- User verification and status updates
- Bulk operations
- User analytics

### **Transaction Management:**
- Monitor all transactions
- Approve/reject transactions
- Fraud detection alerts
- Transaction analytics

### **System Administration:**
- Category management
- Notification system
- System settings
- API key management
- Log monitoring
- Backup management

### **Security & Compliance:**
- Fraud monitoring
- Compliance reports
- Audit logs
- Security alerts
- KYC/AML management

### **Reports:**
- Financial reports
- User activity reports
- Transaction summaries
- Revenue analysis
- Compliance reports

## 🔒 **Security Features**

### **Authentication:**
- Secure password hashing
- Session management
- CSRF protection
- Input validation

### **Authorization:**
- Role-based access control
- Permission-based features
- Middleware protection
- API token management

### **Monitoring:**
- Login tracking
- Activity logging
- Security alerts
- Audit trails

## 🎨 **UI Features**

### **Design:**
- Modern, responsive interface
- Tailwind CSS styling
- Professional admin theme
- Mobile-friendly layout

### **Navigation:**
- Sidebar navigation
- Breadcrumb navigation
- Quick access menus
- Search functionality

### **Components:**
- Data tables
- Charts and graphs
- Form components
- Alert notifications
- Modal dialogs

## 📊 **API Integration**

### **Admin API:**
- RESTful API endpoints
- Token-based authentication
- JSON responses
- Rate limiting

### **Available Endpoints:**
- `GET /admin/dashboard/overview`
- `GET /admin/users`
- `GET /admin/transactions`
- `GET /admin/system/settings`
- `POST /admin/users/bulk-actions`

## 🚨 **Troubleshooting**

### **Common Issues:**

#### **1. "Class 'Inertia\ServiceProvider' not found"**
- Clear all caches: `php artisan config:clear && php artisan cache:clear && php artisan view:clear`
- Remove any remaining Inertia references

#### **2. "Vite manifest not found"**
- Build CSS: `npm run build`
- Check if `public/css/app.css` exists

#### **3. "Admin authentication failed"**
- Verify admin user exists in database
- Check email/password combination
- Ensure admin guard is configured

#### **4. "Route not found"**
- Clear route cache: `php artisan route:clear`
- Check route definitions in `routes/web.php`

### **Debug Commands:**
```bash
# Check admin users
php artisan tinker
>>> App\Models\Admin::all();

# Check routes
php artisan route:list --name=admin

# Check configuration
php artisan config:show auth
```

## 🔄 **Maintenance**

### **Regular Tasks:**
- Monitor admin login logs
- Review security alerts
- Update admin permissions
- Backup admin data
- Monitor system performance

### **Updates:**
- Keep Laravel updated
- Update dependencies
- Review security patches
- Test admin functionality

## 📞 **Support**

For technical support or questions about the admin dashboard:
1. Check this README
2. Review Laravel documentation
3. Check error logs in `storage/logs/`
4. Verify database configuration

---

**Last Updated**: August 2025  
**Version**: 1.0.0  
**Status**: ✅ Production Ready
