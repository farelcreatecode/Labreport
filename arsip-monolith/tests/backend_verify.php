<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Complaint;
use App\Models\Lab;
use App\Models\Category;
use App\Models\Facility;
use App\Policies\ComplaintPolicy;
use App\Services\StatisticsService;

echo "=================================================\n";
echo "       LABREPORT BACKEND VERIFICATION TEST       \n";
echo "=================================================\n\n";

// 1. Data Counts
echo "1. DATABASE COUNTS:\n";
echo " - Users     : " . User::count() . "\n";
echo " - Labs      : " . Lab::count() . "\n";
echo " - Categories: " . Category::count() . "\n";
echo " - Facilities: " . Facility::count() . "\n";
echo " - Complaints: " . Complaint::count() . "\n\n";

// 2. Accessors & Relations
echo "2. ACCESSORS & RELATIONS CHECK:\n";
$firstComplaint = Complaint::with(['user', 'lab', 'category'])->first();
echo " - ID / Report Number : #" . $firstComplaint->id . " => " . $firstComplaint->report_number . "\n";
echo " - Raw Status         : " . $firstComplaint->status . " => Label: " . $firstComplaint->status_label . "\n";
echo " - Pelapor Name       : " . $firstComplaint->user->name . " (NIM: " . $firstComplaint->user->nim_nip . ")\n";
echo " - Lab Name           : " . $firstComplaint->lab->nama_lab . "\n";
echo " - Category Name      : " . $firstComplaint->category->nama_kategori . "\n\n";

// 3. Authorization Policy
echo "3. POLICY AUTHORIZATION CHECK:\n";
$mhsOwner = $firstComplaint->user;
$mhsOther = User::where('role', 'mahasiswa')->where('id', '!=', $mhsOwner->id)->first();
$adminUser = User::where('role', 'admin')->first();
$techUser = User::where('role', 'teknisi')->first();

$policy = new ComplaintPolicy();
echo " - Owner viewing complaint       : " . ($policy->view($mhsOwner, $firstComplaint) ? "PASS (Allowed)" : "FAIL") . "\n";
echo " - Other student viewing         : " . (!$policy->view($mhsOther, $firstComplaint) ? "PASS (Blocked)" : "FAIL") . "\n";
echo " - Admin viewing                 : " . ($policy->view($adminUser, $firstComplaint) ? "PASS (Allowed)" : "FAIL") . "\n";
echo " - Admin updating                : " . ($policy->update($adminUser, $firstComplaint) ? "PASS (Allowed)" : "FAIL") . "\n";
echo " - Unassigned Tech updating      : " . (!$policy->update($techUser, $firstComplaint) ? "PASS (Blocked)" : "FAIL") . "\n\n";

// 4. Statistics Service
echo "4. STATISTICS SERVICE TEST:\n";
$statService = app(StatisticsService::class);
$counts = $statService->getStatusCounts();
echo " - Status Counts: Total=" . $counts['total'] . " | Menunggu=" . $counts['pending'] . " | Diproses=" . $counts['diproses'] . " | Selesai=" . $counts['selesai'] . " | Ditolak=" . $counts['ditolak'] . "\n";

$catCounts = $statService->getCategoryCounts();
echo " - Category Counts: Software=" . $catCounts['software'] . " | Hardware=" . $catCounts['hardware'] . " | Jaringan=" . $catCounts['jaringan'] . "\n";

$catChartData = $statService->getCategoryChartData();
echo " - Category Chart Labels: " . implode(", ", $catChartData['labels']) . "\n";
echo " - Category Chart Data  : " . implode(", ", $catChartData['data']) . "\n";

$chartData = $statService->getStatusChartData();
echo " - Doughnut Chart Labels: " . implode(", ", $chartData['labels']) . "\n";
echo " - Doughnut Chart Data  : " . implode(", ", $chartData['data']) . "\n";

$monthly = $statService->getMonthlyComplaintsCount();
echo " - Monthly Records Count: " . count($monthly) . " months returned\n";

$facilityStats = $statService->getFacilityStats();
echo " - Facility Stats Total Units: " . $facilityStats['total_units'] . " (Baik: " . $facilityStats['total_baik'] . ", Rusak: " . $facilityStats['total_rusak'] . ")\n\n";

// 5. UNUJA Portal Service Test
echo "5. UNUJA PORTAL MICROSERVICE CLIENT TEST:\n";
$unujaService = app(\App\Services\UnujaPortalService::class);
$res = $unujaService->getReferenceData('mahasiswa', '2101001');
echo " - UNUJA Portal Client Status: " . ($res['success'] ? "SUCCESS" : "FAILED") . " (" . $res['message'] . ")\n\n";

echo "=================================================\n";
echo "       ALL VERIFICATION TESTS COMPLETED OK!       \n";
echo "=================================================\n";
