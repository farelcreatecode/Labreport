@echo off
title Stop LabReport Microservices
echo Menghentikan seluruh proses PHP server di port 8000, 8001, 8002, 8003, 8004, 8005...
taskkill /F /IM php.exe /T 2>nul
echo Seluruh service telah dihentikan.
pause
