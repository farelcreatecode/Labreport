<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Laboratory;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\Technician;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminReportController extends Controller
{
    /**
     * Dashboard statistik untuk Admin
     */
    public function dashboard(): JsonResponse
    {
        $total = Report::count();
        $pending = Report::where('status', 'pending')->count();
        $diproses = Report::where('status', 'diproses')->count();
        $selesai = Report::where('status', 'selesai')->count();
        $ditolak = Report::where('status', 'ditolak')->count();

        // Kategori breakdown berdasarkan kategori fasilitas
        $hardwareCount = Report::whereHas('facility', fn ($q) => $q->where('kategori', 'hardware'))->count();
        $softwareCount = Report::whereHas('facility', fn ($q) => $q->where('kategori', 'software'))->count();
        $jaringanCount = Report::whereHas('facility', fn ($q) => $q->where('kategori', 'jaringan'))->count();

        // Rincian status (menunggu/pending, diproses, selesai) per kategori
        $categoryDetails = [
            'software' => [
                'total'    => $softwareCount,
                'pending'  => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'software'))->where('status', 'pending')->count(),
                'diproses' => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'software'))->where('status', 'diproses')->count(),
                'selesai'  => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'software'))->where('status', 'selesai')->count(),
            ],
            'hardware' => [
                'total'    => $hardwareCount,
                'pending'  => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'hardware'))->where('status', 'pending')->count(),
                'diproses' => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'hardware'))->where('status', 'diproses')->count(),
                'selesai'  => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'hardware'))->where('status', 'selesai')->count(),
            ],
            'jaringan' => [
                'total'    => $jaringanCount,
                'pending'  => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'jaringan'))->where('status', 'pending')->count(),
                'diproses' => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'jaringan'))->where('status', 'diproses')->count(),
                'selesai'  => Report::whereHas('facility', fn ($q) => $q->where('kategori', 'jaringan'))->where('status', 'selesai')->count(),
            ],
        ];

        // 5 Laporan terbaru
        $latestReports = Report::with(['laboratory', 'facility', 'technician'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'counts' => [
                    'total'    => $total,
                    'pending'  => $pending,
                    'diproses' => $diproses,
                    'selesai'  => $selesai,
                    'ditolak'  => $ditolak,
                ],
                'categories' => [
                    'hardware' => $hardwareCount,
                    'software' => $softwareCount,
                    'jaringan' => $jaringanCount,
                ],
                'category_details' => $categoryDetails,
                'latest_reports'   => $latestReports,
            ],
        ]);
    }

    /**
     * Daftar seluruh laporan untuk Admin dengan filter & pencarian
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::with(['laboratory', 'facility', 'technician', 'attachments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('laboratory_id')) {
            $query->where('laboratory_id', $request->laboratory_id);
        }

        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        if ($request->filled('kategori')) {
            $cat = $request->kategori;
            $query->whereHas('facility', fn ($q) => $q->where('kategori', $cat));
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('kode_laporan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_pelapor', 'LIKE', "%{$search}%")
                  ->orWhere('kontak_pelapor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_pc', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi_masalah', 'LIKE', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $reports = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * Detail laporan spesifik untuk Admin
     */
    public function show(int $id): JsonResponse
    {
        $report = Report::with([
            'laboratory',
            'facility',
            'technician',
            'attachments',
            'histories',
        ])->find($id);

        if (! $report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $report,
        ]);
    }

    /**
     * Update status laporan, penugasan teknisi, dan tanggapan admin
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $report = Report::find($id);

        if (! $report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status'        => ['required', 'in:pending,diproses,selesai,ditolak'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'tanggapan'     => ['nullable', 'string', 'max:2000'],
        ], [
            'status.required'       => 'Status laporan wajib dipilih.',
            'status.in'             => 'Pilihan status tidak valid.',
            'technician_id.exists'  => 'Teknisi yang dipilih tidak terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi pembaruan laporan gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $authUser = $request->attributes->get('auth_user') ?? ['name' => 'Administrator'];
        $adminName = $authUser['name'] ?? 'Administrator';

        $oldStatus = $report->status;
        $oldTechId = $report->technician_id;

        DB::transaction(function () use ($report, $request, $oldStatus, $oldTechId, $adminName) {
            $report->status = $request->status;
            
            if ($request->has('technician_id')) {
                $report->technician_id = $request->technician_id;
            }

            if ($request->has('tanggapan')) {
                $report->tanggapan = $request->tanggapan;
            }

            $report->save();

            // Catat ke audit history jika ada perubahan status atau penugasan
            $notes = [];
            if ($oldStatus !== $report->status) {
                $notes[] = "Status diubah dari '{$oldStatus}' menjadi '{$report->status}'";
            }
            if ($oldTechId != $report->technician_id) {
                $techName = $report->technician ? $report->technician->nama : 'Tidak Ada';
                $notes[] = "Penugasan teknisi diperbarui: {$techName}";
            }
            if ($request->filled('tanggapan')) {
                $notes[] = "Catatan: " . $request->tanggapan;
            }

            ReportHistory::create([
                'report_id'   => $report->id,
                'status_from' => $oldStatus,
                'status_to'   => $report->status,
                'catatan'     => implode(' | ', $notes) ?: 'Laporan diperbarui oleh admin.',
                'changed_by'  => $adminName . ' (Admin)',
                'created_at'  => now(),
            ]);
        });

        $report->load(['laboratory', 'facility', 'technician', 'attachments', 'histories']);

        return response()->json([
            'success' => true,
            'message' => 'Status laporan ' . $report->kode_laporan . ' berhasil diperbarui.',
            'data'    => $report,
        ]);
    }
}
