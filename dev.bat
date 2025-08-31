@echo off
echo Starting Amen Pay Backend Development Environment...
echo.

echo Building Tailwind CSS...
npm run build:css

echo.
echo Tailwind CSS is now watching for changes!
echo Open http://localhost:8000/public/ui/index.html in your browser
echo.
echo Press Ctrl+C to stop the CSS watcher
echo.

npm run build:css



