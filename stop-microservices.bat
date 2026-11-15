@echo off
title Stop LabReport Microservices
echo ==============================================================
echo    MENGHENTIKAN ARSITEKTUR MICROSERVICES LABREPORT
echo ==============================================================
echo.
cd /d "%~dp0pelaporan-lab"
call stop-microservices.bat
