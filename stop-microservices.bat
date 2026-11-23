@echo off
title Stop LabReport Microservices
cd /d "%~dp0pelaporan-lab"
call stop-microservices.bat
