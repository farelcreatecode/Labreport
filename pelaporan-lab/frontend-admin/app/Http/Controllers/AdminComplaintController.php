<?php

namespace App\Http\Controllers;

use App\Services\GatewayClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminComplaintController extends Controller
{
    protected GatewayClient $gateway;

    public function __construct(GatewayClient $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Daftar Laporan untuk Admin
     */
    public function index(Request $request): View
    {
        $queryParams = $request->only(['status', 'laboratory_id', 'technician_id', 'kategori', 'search', 'page']);
        
        $reportsRes = $this->gateway->get('/admin/reports', $queryParams);
        $labsRes = $this->gateway->get('/admin/laboratories');
        $techsRes = $this->gateway->get('/admin/technicians');

        $reportsData = $reportsRes->json('data') ?? [];
        $reports = $reportsData['data'] ?? [];
        $pagination = [
            'current_page' => $reportsData['current_page'] ?? 1,
            'last_page'    => $reportsData['last_page'] ?? 1,
            'total'        => $reportsData['total'] ?? count($reports),
            'per_page'     => $reportsData['per_page'] ?? 15,
        ];

        $laboratories = $labsRes->json('data') ?? [];
        $technicians = $techsRes->json('data') ?? [];

        return view('admin.complaints.index', compact('reports', 'pagination', 'laboratories', 'technicians'));
    }

    /**
     * Detail Laporan & Form Penugasan / Status
     */
    public function show(int $id): View|RedirectResponse
    {
        $reportRes = $this->gateway->get("/admin/reports/{$id}");
        $techsRes = $this->gateway->get('/admin/technicians');

        if (! $reportRes->successful()) {
            return redirect()->route('admin.complaints.index')
                ->with('error', 'Laporan tidak ditemukan di Report Service.');
        }

        $report = $reportRes->json('data');
        $technicians = $techsRes->json('data') ?? [];

        return view('admin.complaints.show', compact('report', 'technicians'));
    }

    /**
     * Update Status & Tugaskan Teknisi
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status'        => ['required', 'in:pending,diproses,selesai,ditolak'],
            'technician_id' => ['nullable'],
            'tanggapan'     => ['nullable', 'string', 'max:2000'],
        ]);

        $response = $this->gateway->put("/admin/reports/{$id}/status", [
            'status'        => $request->status,
            'technician_id' => $request->technician_id ?: null,
            'tanggapan'     => $request->tanggapan,
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.complaints.show', $id)
                ->with('success', 'Status dan penugasan laporan berhasil diperbarui.');
        }

        $msg = $response->json('message') ?? 'Gagal memperbarui status laporan.';
        return back()->with('error', $msg);
    }
}
