<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Laboratory;
use App\Models\Report;
use App\Models\ReportAttachment;
use App\Models\ReportHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicReportController extends Controller
{
    /**
     * Data master untuk form pelaporan publik (tanpa login)
     */
    public function getFormData(): JsonResponse
    {
        $laboratories = Laboratory::select('id', 'nama_lab', 'lokasi', 'kapasitas')
            ->orderBy('nama_lab')
            ->get();

        $facilities = Facility::select('id', 'laboratory_id', 'nama_fasilitas', 'kategori', 'kondisi')
            ->orderBy('nama_fasilitas')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'laboratories' => $laboratories,
                'facilities'   => $facilities,
            ],
        ]);
    }

    /**
     * Submit Laporan Kerusakan Baru (Publik / Tanpa Login)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_pelapor'      => ['required', 'string', 'max:150'],
            'kontak_pelapor'    => ['required', 'string', 'max:100'],
            'laboratory_id'     => ['required', 'exists:laboratories,id'],
            'facility_id'       => ['required', 'exists:facilities,id'],
            'nomor_pc'          => ['nullable', 'string', 'max:50'],
            'deskripsi_masalah' => ['required', 'string', 'min:5', 'max:2000'],
            'foto_bukti'        => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // max 5MB
        ], [
            'nama_pelapor.required'      => 'Nama pelapor wajib diisi.',
            'kontak_pelapor.required'    => 'Kontak pelapor (Email / No. WhatsApp) wajib diisi.',
            'laboratory_id.required'     => 'Laboratorium wajib dipilih.',
            'laboratory_id.exists'       => 'Laboratorium yang dipilih tidak valid.',
            'facility_id.required'       => 'Fasilitas wajib dipilih.',
            'facility_id.exists'         => 'Fasilitas yang dipilih tidak valid.',
            'deskripsi_masalah.required' => 'Deskripsi kerusakan wajib diisi.',
            'foto_bukti.image'           => 'File bukti harus berupa gambar (JPG, PNG).',
            'foto_bukti.max'             => 'Ukuran foto bukti maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data laporan gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $uploadedFile = null;
        $savedPath = null;

        if ($request->hasFile('foto_bukti') && $request->file('foto_bukti')->isValid()) {
            $uploadedFile = $request->file('foto_bukti');
            $savedPath = $uploadedFile->store('attachments', 'public');
        }

        try {
            $report = DB::transaction(function () use ($request, $uploadedFile, $savedPath) {
                $kodeLaporan = Report::generateKode();

                $report = Report::create([
                    'kode_laporan'      => $kodeLaporan,
                    'nama_pelapor'      => trim($request->nama_pelapor),
                    'kontak_pelapor'    => trim($request->kontak_pelapor),
                    'laboratory_id'     => $request->laboratory_id,
                    'facility_id'       => $request->facility_id,
                    'nomor_pc'          => $request->nomor_pc ? trim($request->nomor_pc) : null,
                    'deskripsi_masalah' => trim($request->deskripsi_masalah),
                    'status'            => 'pending',
                ]);

                // Simpan Attachment jika ada
                if ($uploadedFile && $savedPath) {
                    ReportAttachment::create([
                        'report_id' => $report->id,
                        'file_path' => $savedPath,
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                        'mime_type' => $uploadedFile->getClientMimeType(),
                    ]);
                }

                // Catat Riwayat Awal
                ReportHistory::create([
                    'report_id'   => $report->id,
                    'status_from' => null,
                    'status_to'   => 'pending',
                    'catatan'     => 'Laporan berhasil dibuat oleh pelapor.',
                    'changed_by'  => $request->nama_pelapor . ' (Pelapor)',
                    'created_at'  => now(),
                ]);

                return $report;
            });

            return response()->json([
                'success' => true,
                'message' => 'Laporan kerusakan fasilitas berhasil dikirim.',
                'data'    => [
                    'id'           => $report->id,
                    'kode_laporan' => $report->kode_laporan,
                    'status'       => $report->status,
                    'created_at'   => $report->created_at->toIso8601String(),
                ],
            ], 201);

        } catch (\Throwable $e) {
            if ($savedPath) {
                Storage::disk('public')->delete($savedPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan laporan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cek status laporan publik menggunakan kode laporan
     */
    public function showPublic(string $kode): JsonResponse
    {
        $report = Report::with([
            'laboratory:id,nama_lab,lokasi',
            'facility:id,nama_fasilitas,kategori',
            'technician:id,nama,spesialisasi',
            'attachments',
            'histories',
        ])
        ->where('kode_laporan', $kode)
        ->first();

        if (! $report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan dengan nomor ' . $kode . ' tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $report,
        ]);
    }
}
