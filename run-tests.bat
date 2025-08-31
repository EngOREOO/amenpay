@echo off
echo ========================================
echo P-Finance Backend Test Runner
echo ========================================
echo.

echo [1/5] Running Unit Tests...
php artisan test --testsuite=Unit --coverage-text
if %errorlevel% neq 0 (
    echo ❌ Unit tests failed!
    pause
    exit /b 1
)
echo ✅ Unit tests passed!
echo.

echo [2/5] Running Feature Tests...
php artisan test --testsuite=Feature --coverage-text
if %errorlevel% neq 0 (
    echo ❌ Feature tests failed!
    pause
    exit /b 1
)
echo ✅ Feature tests passed!
echo.

echo [3/5] Running Integration Tests...
php artisan test --testsuite=Integration --coverage-text
if %errorlevel% neq 0 (
    echo ❌ Integration tests failed!
    pause
    exit /b 1
)
echo ✅ Integration tests passed!
echo.

echo [4/5] Running All Tests with Coverage...
php artisan test --coverage-html=coverage/html --coverage-text=coverage/coverage.txt --coverage-clover=coverage/clover.xml
if %errorlevel% neq 0 (
    echo ❌ Tests with coverage failed!
    pause
    exit /b 1
)
echo ✅ All tests with coverage passed!
echo.

echo [5/5] Running Performance Tests...
php artisan test --filter="PerformanceTest"
if %errorlevel% neq 0 (
    echo ❌ Performance tests failed!
    pause
    exit /b 1
)
echo ✅ Performance tests passed!
echo.

echo ========================================
echo 🎉 All Tests Completed Successfully!
echo ========================================
echo.
echo 📊 Coverage Report: coverage/html/index.html
echo 📝 Text Report: coverage/coverage.txt
echo 📋 XML Report: coverage/clover.xml
echo.
echo Press any key to open coverage report...
pause >nul

start coverage/html/index.html
echo Coverage report opened in browser!
pause
