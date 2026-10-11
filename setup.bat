@echo off
cd /d "%~dp0"
if not exist ".env" copy .env.example .env
if exist "C:\xampp\mysql\bin\mysql.exe" (
  "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS dashboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
)
php artisan key:generate --force
php artisan migrate --seed --force
echo.
echo Dashboard is ready. Create your staff account at http://127.0.0.1:8000/register
echo Starting server...
php artisan serve
