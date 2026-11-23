<?php

namespace App\Http\Controllers;

use App\Services\GatewayClient;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    protected GatewayClient $gateway;

    public function __construct(GatewayClient $gateway)
    {
        $this->gateway = $gateway;
    }

    public function index(): View
    {
        $response = $this->gateway->get('/admin/dashboard');
        $dashboardData = $response->json('data') ?? [
            'counts' => ['total' => 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0, 'ditolak' => 0],
            'categories' => ['hardware' => 0, 'software' => 0, 'jaringan' => 0],
            'latest_reports' => [],
        ];

        // Ambil riwayat log notifikasi dari Notification Service (Port 8005 via Gateway)
        $notifResponse = $this->gateway->get('/notifications/logs?limit=6');
        $notificationLogs = $notifResponse->json('data') ?? [];

        return view('admin.dashboard', compact('dashboardData', 'notificationLogs'));
    }
}
