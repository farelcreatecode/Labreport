<?php

require __DIR__ . '/../pelaporan-lab/frontend/vendor/autoload.php';
$app = require_once __DIR__ . '/../pelaporan-lab/frontend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "[1/2] Rendering pelapor.complaints.track... ";
$htmlTrack = view('pelapor.complaints.track', [
    'kode' => '',
    'report' => null,
    'recentReports' => [
        [
            'id' => 1,
            'kode_laporan' => '#LR-202611-0001',
            'nama_pelapor' => 'Ah*** Fa***',
            'laboratorium' => 'Lab Multimedia',
            'fasilitas' => 'PC-01',
            'kategori' => 'hardware',
            'nomor_pc' => 'PC-01',
            'deskripsi_masalah' => 'Monitor blank',
            'status' => 'selesai',
            'teknisi' => 'Ahmad Fadli',
            'tanggal_update' => '15 Nov 2026 21:00',
        ]
    ],
])->render();
echo "SUCCESS (" . strlen($htmlTrack) . " bytes)\n";

echo "[2/2] Rendering admin.dashboard... ";
$htmlDash = view('admin.dashboard', [
    'dashboardData' => [
        'counts' => ['total' => 10, 'pending' => 3, 'diproses' => 4, 'selesai' => 3, 'ditolak' => 0],
        'categories' => ['hardware' => 5, 'software' => 3, 'jaringan' => 2],
        'category_details' => [
            'software' => ['total' => 3, 'pending' => 1, 'diproses' => 1, 'selesai' => 1],
            'hardware' => ['total' => 5, 'pending' => 1, 'diproses' => 2, 'selesai' => 2],
            'jaringan' => ['total' => 2, 'pending' => 1, 'diproses' => 1, 'selesai' => 0],
        ],
        'latest_reports' => [],
    ],
])->render();
echo "SUCCESS (" . strlen($htmlDash) . " bytes)\n";

echo "\nALL VIEWS RENDERED PERFECTLY!\n";
