<?php

namespace App\Http\Controllers;

use App\Services\GatewayClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelaporController extends Controller
{
    protected GatewayClient $gateway;

    public function __construct(GatewayClient $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Halaman Utama / Form Pelaporan Publik (Tanpa Login)
     */
    public function create(): View
    {
        $response = $this->gateway->get('/public/form-data');
        $data = $response->json('data') ?? [];

        $laboratories = $data['laboratories'] ?? [];
        $facilities = $data['facilities'] ?? [];

        return view('pelapor.complaints.create', compact('laboratories', 'facilities'));
    }

    /**
     * Kirim Laporan Kerusakan ke API Gateway -> Report Service
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_pelapor'      => ['required', 'string', 'max:150'],
            'kontak_pelapor'    => ['required', 'string', 'max:100'],
            'laboratory_id'     => ['required'],
            'facility_id'       => ['required'],
            'nomor_pc'          => ['nullable', 'string', 'max:50'],
            'deskripsi_masalah' => ['required', 'string', 'min:5'],
            'foto_bukti'        => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        $postData = $request->except(['_token', 'foto_bukti']);
        $file = $request->file('foto_bukti');

        $response = $this->gateway->postMultipart('/public/reports', $postData, $file);

        if ($response->successful()) {
            $reportData = $response->json('data') ?? [];
            $kode = $reportData['kode_laporan'] ?? '-';

            return redirect()->route('pelapor.track', ['kode' => $kode])
                ->with('success', "Laporan kerusakan berhasil dikirim dengan Nomor Registrasi: {$kode}. Simpan kode ini untuk memantau status perbaikan!");
        }

        $errorMsg = $response->json('message') ?? 'Gagal mengirim laporan kerusakan ke sistem.';
        $errors = $response->json('errors') ?? [];

        return back()->withInput()->withErrors($errors)->with('error', $errorMsg);
    }

    /**
     * Halaman Lacak Laporan Publik Berdasarkan Kode Laporan
     */
    public function track(Request $request): View
    {
        $kode = trim($request->input('kode', ''));
        $report = null;

        if (! empty($kode)) {
            $response = $this->gateway->get("/public/reports/{$kode}");
            if ($response->successful()) {
                $report = $response->json('data');
            } else {
                session()->flash('error', "Laporan dengan nomor '{$kode}' tidak ditemukan.");
            }
        }

        return view('pelapor.complaints.track', compact('report', 'kode'));
    }
}
