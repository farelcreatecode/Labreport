@echo off
title Start LabReport Microservices
echo ==============================================================
echo    MENJALANKAN ARSITEKTUR MICROSERVICES LABREPORT
echo ==============================================================
echo.
echo 1. Menjalankan Auth Service di Port 8002...
start "Auth Service (:8002)" cmd /k "cd /d %~dp0auth-service && php artisan serve --port=8002"

echo 2. Menjalankan Report Service di Port 8003...
start "Report Service (:8003)" cmd /k "cd /d %~dp0report-service && php artisan serve --port=8003"

echo 3. Menjalankan API Gateway di Port 8001...
start "API Gateway (:8001)" cmd /k "cd /d %~dp0api-gateway && php artisan serve --port=8001"

echo 4. Menjalankan Frontend Portal di Port 8000...
start "Frontend (:8000)" cmd /k "cd /d %~dp0frontend && php artisan serve --port=8000"

echo.
echo ==============================================================
echo Seluruh 4 Service Berhasil Dijalankan!
echo - Frontend Website : http://127.0.0.1:8000
echo - API Gateway      : http://127.0.0.1:8001/api
echo - Auth Service     : http://127.0.0.1:8002/api
echo - Report Service   : http://127.0.0.1:8003/api
echo ==============================================================
pause
