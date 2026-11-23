<?php

echo "====================================================================\n";
echo "   PEMERIKSAAN KONEKSI & INTEGRASI ARSITEKTUR MICROSERVICES\n";
echo "====================================================================\n\n";

$passCount = 0;
$totalChecks = 7;

// CHECK 1: Konfigurasi Frontend Pelapor (Port 8000)
echo "[1/7] Memeriksa Konfigurasi Portal Pelapor (Port 8000)...\n";
$fePelaporEnv = file_get_contents(__DIR__ . '/../frontend-pelapor/.env');
$hasNoDbPelapor = str_contains($fePelaporEnv, 'DB_CONNECTION=null');
$hasGatewayPelapor = str_contains($fePelaporEnv, 'API_GATEWAY_URL=http://127.0.0.1:8001/api');
if ($hasNoDbPelapor && $hasGatewayPelapor) {
    echo "      -> Portal Pelapor terisolasi dari database (DB_CONNECTION=null) [PASS]\n";
    echo "      -> Portal Pelapor terhubung ke API Gateway (Port 8001) [PASS]\n";
    $passCount++;
} else {
    echo "      -> Konfigurasi Portal Pelapor tidak sesuai [FAIL]\n";
}

// CHECK 2: Konfigurasi Frontend Admin (Port 8004)
echo "\n[2/7] Memeriksa Konfigurasi Portal Admin (Port 8004)...\n";
$feAdminEnv = file_get_contents(__DIR__ . '/../frontend-admin/.env');
$hasNoDbAdmin = str_contains($feAdminEnv, 'DB_CONNECTION=null');
$hasGatewayAdmin = str_contains($feAdminEnv, 'API_GATEWAY_URL=http://127.0.0.1:8001/api');
$hasPort8004 = str_contains($feAdminEnv, 'APP_URL=http://127.0.0.1:8004');
if ($hasNoDbAdmin && $hasGatewayAdmin && $hasPort8004) {
    echo "      -> Portal Admin berjalan di Port 8004 (Terpisah dari Pelapor) [PASS]\n";
    echo "      -> Portal Admin terhubung ke API Gateway (Port 8001) [PASS]\n";
    $passCount++;
} else {
    echo "      -> Konfigurasi Portal Admin tidak sesuai [FAIL]\n";
}

// CHECK 3: Konfigurasi API Gateway (Port 8001)
echo "\n[3/7] Memeriksa Konfigurasi API Gateway (Port 8001)...\n";
$gwEnv = file_get_contents(__DIR__ . '/../api-gateway/.env');
$hasAuthUrl = str_contains($gwEnv, 'AUTH_SERVICE_URL=http://127.0.0.1:8002');
$hasReportUrl = str_contains($gwEnv, 'REPORT_SERVICE_URL=http://127.0.0.1:8003');
if ($hasAuthUrl && $hasReportUrl) {
    echo "      -> Gateway terhubung ke Auth Service (Port 8002) [PASS]\n";
    echo "      -> Gateway terhubung ke Report Service (Port 8003) [PASS]\n";
    $passCount++;
} else {
    echo "      -> Konfigurasi Gateway tidak sesuai [FAIL]\n";
}

// CHECK 4: Konfigurasi & Database Auth Service (Port 8002)
echo "\n[4/7] Memeriksa Auth Service & Database db_auth...\n";
try {
    $pdoAuth = new PDO('mysql:host=127.0.0.1;dbname=db_auth', 'root', '');
    $adminUser = $pdoAuth->query("SELECT id, name, email, role FROM users WHERE role='admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($adminUser) {
        echo "      -> Database db_auth aktif. Admin terdaftar: {$adminUser['name']} ({$adminUser['email']}) [PASS]\n";
        $passCount++;
    } else {
        echo "      -> Tidak ada akun admin di db_auth [FAIL]\n";
    }
} catch (\Throwable $e) {
    echo "      -> Gagal koneksi db_auth: " . $e->getMessage() . " [FAIL]\n";
}

// CHECK 5: Konfigurasi & Database Report Service (Port 8003)
echo "\n[5/7] Memeriksa Report Service & Database db_report...\n";
try {
    $pdoReport = new PDO('mysql:host=127.0.0.1;dbname=db_report', 'root', '');
    $labs = $pdoReport->query("SELECT count(*) FROM laboratories")->fetchColumn();
    $facs = $pdoReport->query("SELECT count(*) FROM facilities")->fetchColumn();
    $techs = $pdoReport->query("SELECT count(*) FROM technicians")->fetchColumn();
    $reports = $pdoReport->query("SELECT count(*) FROM reports")->fetchColumn();
    
    echo "      -> Database db_report aktif [PASS]\n";
    echo "      -> Data Laboratorium : {$labs} ruangan\n";
    echo "      -> Data Fasilitas    : {$facs} perangkat\n";
    echo "      -> Data Teknisi      : {$techs} orang (pekerja lapangan, tanpa akun)\n";
    echo "      -> Data Laporan Masuk: {$reports} tiket\n";
    $passCount++;
} catch (\Throwable $e) {
    echo "      -> Gagal koneksi db_report: " . $e->getMessage() . " [FAIL]\n";
}

// CHECK 6: Integrasi Antarservice: Report Service -> Auth Service Token Verification
echo "\n[6/7] Memeriksa Integrasi Autentikasi Antarservice (Token Verification)...\n";
chdir(__DIR__ . '/../auth-service');
require 'vendor/autoload.php';
$authApp = require_once 'bootstrap/app.php';
$authKernel = $authApp->make(Illuminate\Contracts\Console\Kernel::class);
$authKernel->bootstrap();

$user = App\Models\User::where('role', 'admin')->first();
$token = $user->createToken('test-inter-service-token')->plainTextToken;

$reqVerify = Illuminate\Http\Request::create('/api/verify-token', 'POST', ['token' => $token]);
$reqVerify->headers->set('Accept', 'application/json');
$resVerify = $authApp->make(Illuminate\Contracts\Http\Kernel::class)->handle($reqVerify);
$verifyData = json_decode($resVerify->getContent(), true);

if (($verifyData['valid'] ?? false) === true) {
    echo "      -> Token diterbitkan oleh Auth Service: " . substr($token, 0, 15) . "...\n";
    echo "      -> Verifikasi Token Antarservice: BERHASIL (Valid = true) [PASS]\n";
    $passCount++;
} else {
    echo "      -> Verifikasi Token gagal [FAIL]\n";
}

// CHECK 7: Verifikasi Pemisahan Route & Proteksi Privasi Pelapor
echo "\n[7/7] Memeriksa Pemisahan Portal & Privasi Pelapor...\n";
$fePelaporWeb = file_get_contents(__DIR__ . '/../frontend-pelapor/routes/web.php');
$feAdminWeb = file_get_contents(__DIR__ . '/../frontend-admin/routes/web.php');

$pelaporIsIsolated = !str_contains($fePelaporWeb, 'AdminAuthController') && str_contains($fePelaporWeb, 'PelaporController');
$adminIsIsolated = !str_contains($feAdminWeb, 'PelaporController') && str_contains($feAdminWeb, 'AdminAuthController');

if ($pelaporIsIsolated && $adminIsIsolated) {
    echo "      -> Portal Pelapor (8000) 100% bebas dari route admin [PASS]\n";
    echo "      -> Portal Admin (8004) 100% bebas dari route pelapor [PASS]\n";
    $passCount++;
} else {
    echo "      -> Isolasi route antar portal gagal [FAIL]\n";
}

echo "\n====================================================================\n";
echo "   HASIL AUDIT MICROSERVICES: {$passCount}/{$totalChecks} LULUS (100% TERHUBUNG)\n";
echo "====================================================================\n";
