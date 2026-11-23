@echo off
title Start LabReport Microservices (6 Services)
echo ==============================================================
echo    MENJALANKAN ARSITEKTUR MICROSERVICES LABREPORT
echo ==============================================================
echo.
echo 1. Menjalankan Auth Service di Port 8002...
start "Auth Service (:8002)" cmd /k "cd /d %~dp0auth-service && php artisan serve --port=8002"

echo 2. Menjalankan Report Service di Port 8003...
start "Report Service (:8003)" cmd /k "cd /d %~dp0report-service && php artisan serve --port=8003"

echo 3. Menjalankan Notification Service di Port 8005...
start "Notification Service (:8005)" cmd /k "cd /d %~dp0notification-service && php artisan serve --port=8005"

echo 4. Menjalankan API Gateway di Port 8001...
start "API Gateway (:8001)" cmd /k "cd /d %~dp0api-gateway && php artisan serve --port=8001"

echo 5. Menjalankan Frontend Pelapor di Port 8000...
start "Frontend Pelapor (:8000)" cmd /k "cd /d %~dp0frontend-pelapor && php artisan serve --port=8000"

echo 6. Menjalankan Frontend Admin di Port 8004...
start "Frontend Admin (:8004)" cmd /k "cd /d %~dp0frontend-admin && php artisan serve --port=8004"

echo.
echo ==============================================================
echo Seluruh 6 Service Berhasil Dijalankan!
echo - Portal Pelapor (Publik)       : http://127.0.0.1:8000
echo - Portal Admin (Khusus)         : http://127.0.0.1:8004/login
echo - API Gateway                   : http://127.0.0.1:8001/api
echo - Auth Service (db_auth)        : http://127.0.0.1:8002/api
echo - Report Service (db_report)    : http://127.0.0.1:8003/api
echo - Notification Service (db_not) : http://127.0.0.1:8005/api
echo ==============================================================
pause
