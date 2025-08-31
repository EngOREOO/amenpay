@echo off
echo ========================================
echo P-Finance Backend Setup Script
echo ========================================
echo.

echo [1/6] Installing Composer dependencies...
composer install
if %errorlevel% neq 0 (
    echo Error: Failed to install dependencies
    pause
    exit /b 1
)

echo.
echo [2/6] Copying environment file...
if not exist .env (
    copy .env.example .env
    echo Environment file created
) else (
    echo Environment file already exists
)

echo.
echo [3/6] Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo Error: Failed to generate application key
    pause
    exit /b 1
)

echo.
echo [4/6] Running database migrations...
php artisan migrate
if %errorlevel% neq 0 (
    echo Error: Failed to run migrations
    echo Please check your database configuration in .env file
    pause
    exit /b 1
)

echo.
echo [5/6] Seeding database with categories...
php artisan db:seed --class=CategorySeeder
if %errorlevel% neq 0 (
    echo Warning: Failed to seed categories
    echo You can run this manually later with: php artisan db:seed --class=CategorySeeder
)

echo.
echo [6/6] Setup completed successfully!
echo.
echo ========================================
echo Next Steps:
echo ========================================
echo 1. Start the development server:
echo    php artisan serve
echo.
echo 2. Test the API:
echo    curl http://localhost:8000/api/test
echo.
echo 3. Register a new user:
echo    curl -X POST http://localhost:8000/api/auth/register ^
echo         -H "Content-Type: application/json" ^
echo         -d "{\"phone\":\"+966501234567\",\"name\":\"Test User\",\"national_id\":\"1234567890\"}"
echo.
echo ========================================
echo Setup completed!
echo ========================================
pause
