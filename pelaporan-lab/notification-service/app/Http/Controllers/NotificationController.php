<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Mengambil riwayat log seluruh notifikasi yang telah terkirim
     */
    public function getLogs(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 20);
        $role = $request->query('role');

        $query = NotificationLog::latest();
        if ($role) {
            $query->where('recipient_role', $role);
        }

        $logs = $query->take($limit)->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar riwayat notifikasi berhasil dimuat.',
            'total'   => NotificationLog::count(),
            'data'    => $logs,
        ]);
    }

    /**
     * 1. Notifikasi ke Admin ketika ada laporan baru masuk dari mahasiswa
     */
    public function sendReportCreated(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kode_laporan'   => ['required', 'string'],
            'nama_pelapor'   => ['required', 'string'],
            'kontak_pelapor' => ['required', 'string'],
            'laboratorium'   => ['required', 'string'],
            'fasilitas'      => ['required', 'string'],
            'deskripsi'      => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data notifikasi laporan baru tidak lengkap.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $title = "Laporan Masuk: {$request->kode_laporan}";
        $message = "Halo Admin, ada laporan kerusakan baru dari {$request->nama_pelapor} untuk fasilitas [{$request->fasilitas}] di ruang [{$request->laboratorium}]. Mohon segera diperiksa dan ditugaskan ke teknisi.";

        $log = NotificationLog::create([
            'type'             => 'report_created',
            'recipient_role'   => 'admin',
            'recipient_name'   => 'Administrator Lab',
            'recipient_target' => 'admin@labreport.com',
            'channel'          => 'in_app',
            'title'            => $title,
            'message'          => $message,
            'payload'          => $request->all(),
            'status'           => 'sent',
            'sent_at'          => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi laporan baru berhasil dikirim ke Admin.',
            'data'    => $log,
        ], 201);
    }

    /**
     * 2. Notifikasi ketika teknisi baru ditugaskan ke laporan
     */
    public function sendTechnicianAssigned(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kode_laporan'       => ['required', 'string'],
            'technician_name'    => ['required', 'string'],
            'technician_kontak'  => ['nullable', 'string'],
            'nama_pelapor'       => ['nullable', 'string'],
            'kontak_pelapor'     => ['nullable', 'string'],
            'laboratorium'       => ['required', 'string'],
            'fasilitas'          => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data penugasan teknisi tidak lengkap.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // A. Notifikasi ke Teknisi Lapangan (WhatsApp)
        $techTitle = "Tugas Perbaikan: {$request->kode_laporan}";
        $techMessage = "Halo {$request->technician_name}, Anda mendapat tugas perbaikan fasilitas [{$request->fasilitas}] di ruang [{$request->laboratorium}] untuk tiket {$request->kode_laporan}. Silakan cek ke lapangan.";

        $techLog = NotificationLog::create([
            'type'             => 'technician_assigned',
            'recipient_role'   => 'technician',
            'recipient_name'   => $request->technician_name,
            'recipient_target' => $request->technician_kontak ?? 'WhatsApp Teknisi',
            'channel'          => 'whatsapp',
            'title'            => $techTitle,
            'message'          => $techMessage,
            'payload'          => $request->all(),
            'status'           => 'delivered',
            'sent_at'          => now(),
        ]);

        // B. Notifikasi ke Mahasiswa Pelapor (WhatsApp / SMS)
        if ($request->filled('kontak_pelapor')) {
            $studentTitle = "Penugasan Teknisi: {$request->kode_laporan}";
            $studentMessage = "Halo {$request->nama_pelapor}, laporan kerusakan fasilitas [{$request->fasilitas}] Anda ({$request->kode_laporan}) telah ditugaskan kepada Teknisi: {$request->technician_name}.";

            NotificationLog::create([
                'type'             => 'technician_assigned',
                'recipient_role'   => 'student',
                'recipient_name'   => $request->nama_pelapor,
                'recipient_target' => $request->kontak_pelapor,
                'channel'          => 'whatsapp',
                'title'            => $studentTitle,
                'message'          => $studentMessage,
                'payload'          => $request->all(),
                'status'           => 'delivered',
                'sent_at'          => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi penugasan berhasil dikirim ke Teknisi dan Pelapor.',
            'data'    => $techLog,
        ], 201);
    }

    /**
     * 3. Notifikasi ke mahasiswa ketika status laporan berubah (Pending -> Diproses -> Selesai)
     */
    public function sendStatusChanged(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kode_laporan'   => ['required', 'string'],
            'nama_pelapor'   => ['required', 'string'],
            'kontak_pelapor' => ['required', 'string'],
            'old_status'     => ['required', 'string'],
            'new_status'     => ['required', 'string'],
            'catatan'        => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembaruan status tidak lengkap.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $statusLabel = ucfirst($request->new_status);
        $title = "Update Status Tiket {$request->kode_laporan}: {$statusLabel}";
        $catatanInfo = $request->filled('catatan') ? " Catatan: \"{$request->catatan}\"." : "";
        $message = "Halo {$request->nama_pelapor}, status laporan kerusakan {$request->kode_laporan} Anda saat ini telah diperbarui menjadi [{$statusLabel}].{$catatanInfo} Terima kasih atas laporan Anda.";

        $log = NotificationLog::create([
            'type'             => 'status_changed',
            'recipient_role'   => 'student',
            'recipient_name'   => $request->nama_pelapor,
            'recipient_target' => $request->kontak_pelapor,
            'channel'          => 'whatsapp',
            'title'            => $title,
            'message'          => $message,
            'payload'          => $request->all(),
            'status'           => 'delivered',
            'sent_at'          => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi pembaruan status berhasil dikirim ke mahasiswa/pelapor.',
            'data'    => $log,
        ], 201);
    }
}
