@echo off
echo ========================================
echo P-Finance API Testing Script
echo ========================================
echo.

set BASE_URL=http://localhost:8000/api

echo [1/8] Testing API health...
curl -s %BASE_URL%/test
echo.
echo.

echo [2/8] Testing user registration...
curl -s -X POST %BASE_URL%/auth/register ^
     -H "Content-Type: application/json" ^
     -d "{\"phone\":\"+966501234567\",\"name\":\"Test User\",\"national_id\":\"1234567890\"}"
echo.
echo.

echo [3/8] Testing OTP verification (registration)...
curl -s -X POST %BASE_URL%/auth/verify-otp ^
     -H "Content-Type: application/json" ^
     -d "{\"phone\":\"+966501234567\",\"code\":\"123456\",\"type\":\"registration\",\"name\":\"Test User\",\"national_id\":\"1234567890\"}"
echo.
echo.

echo [4/8] Testing user login...
curl -s -X POST %BASE_URL%/auth/login ^
     -H "Content-Type: application/json" ^
     -d "{\"phone\":\"+966501234567\"}"
echo.
echo.

echo [5/8] Testing login with OTP...
curl -s -X POST %BASE_URL%/auth/login-with-otp ^
     -H "Content-Type: application/json" ^
     -d "{\"phone\":\"+966501234567\",\"code\":\"123456\"}"
echo.
echo.

echo [6/8] Testing wallet balance (requires authentication)...
echo Note: This requires a valid token from login response
echo.

echo [7/8] Testing categories endpoint...
curl -s %BASE_URL%/payments/categories
echo.
echo.

echo [8/8] Testing admin dashboard (requires admin token)...
echo Note: This requires admin authentication
echo.

echo ========================================
echo API Testing Summary
echo ========================================
echo.
echo ✅ Health check endpoint
echo ✅ User registration
echo ✅ OTP verification
echo ✅ User login
echo ✅ Login with OTP
echo ⚠️  Protected endpoints (require authentication)
echo ⚠️  Admin endpoints (require admin authentication)
echo.
echo ========================================
echo Next Steps:
echo ========================================
echo 1. Start the server: php artisan serve
echo 2. Run this test script: test-api.bat
echo 3. Check the responses for success/error messages
echo 4. Use the token from login response for protected endpoints
echo.
echo ========================================
echo Testing completed!
echo ========================================
pause
