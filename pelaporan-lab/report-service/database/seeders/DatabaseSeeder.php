<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Laboratory;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\Technician;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Laboratories
        $lab1 = Laboratory::create([
            'nama_lab'  => 'Lab Komputer Dasar',
            'lokasi'    => 'Gedung A, Lantai 2',
            'kapasitas' => 35,
            'deskripsi' => 'Laboratorium untuk praktikum pemrograman dasar dan aplikasi perkantoran.',
        ]);

        $lab2 = Laboratory::create([
            'nama_lab'  => 'Lab Jaringan & Cyber Security',
            'lokasi'    => 'Gedung B, Lantai 1',
            'kapasitas' => 30,
            'deskripsi' => 'Laboratorium jaringan komputer, mikrotik, cisco, dan simulasi keamanan siber.',
        ]);

        $lab3 = Laboratory::create([
            'nama_lab'  => 'Lab Software Engineering',
            'lokasi'    => 'Gedung C, Lantai 3',
            'kapasitas' => 40,
            'deskripsi' => 'Laboratorium rekayasa perangkat lunak, web programming, dan mobile apps.',
        ]);

        // 2. Facilities
        $fac1 = Facility::create(['laboratory_id' => $lab1->id, 'nama_fasilitas' => 'Komputer PC (PC-01 s/d PC-35)', 'kategori' => 'hardware', 'kondisi' => 'baik']);
        $fac2 = Facility::create(['laboratory_id' => $lab1->id, 'nama_fasilitas' => 'Monitor LED 24 Inch', 'kategori' => 'hardware', 'kondisi' => 'baik']);
        $fac3 = Facility::create(['laboratory_id' => $lab1->id, 'nama_fasilitas' => 'Printer Epson L3210', 'kategori' => 'hardware', 'kondisi' => 'rusak_ringan']);
        $fac4 = Facility::create(['laboratory_id' => $lab2->id, 'nama_fasilitas' => 'Kabel LAN & Konektor RJ45', 'kategori' => 'jaringan', 'kondisi' => 'baik']);
        $fac5 = Facility::create(['laboratory_id' => $lab2->id, 'nama_fasilitas' => 'Switch Hub Cisco 24 Port', 'kategori' => 'jaringan', 'kondisi' => 'baik']);
        $fac6 = Facility::create(['laboratory_id' => $lab3->id, 'nama_fasilitas' => 'Visual Studio Code & Git', 'kategori' => 'software', 'kondisi' => 'baik']);
        $fac7 = Facility::create(['laboratory_id' => $lab3->id, 'nama_fasilitas' => 'Laragon Web Server Environment', 'kategori' => 'software', 'kondisi' => 'baik']);

        // 3. Technicians (Data Lapangan, Tanpa Login)
        $tech1 = Technician::create([
            'nama'         => 'Ahmad Fadli, S.Kom',
            'kontak'       => '081234567890',
            'spesialisasi' => 'Hardware & PC Troubleshooting',
            'status'       => 'aktif',
        ]);

        $tech2 = Technician::create([
            'nama'         => 'Budi Santoso, A.Md',
            'kontak'       => '082345678901',
            'spesialisasi' => 'Infrastruktur Jaringan & Mikrotik',
            'status'       => 'aktif',
        ]);

        // 4. Sample Reports
        $rep1 = Report::create([
            'kode_laporan'      => '#LR-202611-0001',
            'nama_pelapor'      => 'Ahmad Fauzi',
            'kontak_pelapor'    => 'fauzi@student.univ.ac.id',
            'laboratory_id'     => $lab1->id,
            'facility_id'       => $fac1->id,
            'nomor_pc'          => 'PC-04',
            'deskripsi_masalah' => 'PC tidak mau booting ke Windows, lampu indikator power berkedip merah dan ada bunyi beep 3 kali.',
            'status'            => 'diproses',
            'technician_id'     => $tech1->id,
            'tanggapan'         => 'Sedang dicek modul RAM PC-04 oleh teknisi Ahmad Fadli.',
        ]);

        ReportHistory::create([
            'report_id'   => $rep1->id,
            'status_from' => null,
            'status_to'   => 'pending',
            'catatan'     => 'Laporan dikirim oleh pelapor.',
            'changed_by'  => 'Ahmad Fauzi (Pelapor)',
            'created_at'  => now()->subDays(2),
        ]);

        ReportHistory::create([
            'report_id'   => $rep1->id,
            'status_from' => 'pending',
            'status_to'   => 'diproses',
            'catatan'     => "Ditugaskan ke teknisi {$tech1->nama}. Catatan: Sedang dicek modul RAM PC-04.",
            'changed_by'  => 'Administrator (Admin)',
            'created_at'  => now()->subDay(),
        ]);

        $rep2 = Report::create([
            'kode_laporan'      => '#LR-202611-0002',
            'nama_pelapor'      => 'Siti Nurhaliza',
            'kontak_pelapor'    => '085712345678',
            'laboratory_id'     => $lab2->id,
            'facility_id'       => $fac4->id,
            'nomor_pc'          => 'PC-12',
            'deskripsi_masalah' => 'Koneksi internet di PC-12 sering putus-nyambung (unidentified network), kabel LAN tampak longgar.',
            'status'            => 'pending',
            'technician_id'     => null,
            'tanggapan'         => null,
        ]);

        ReportHistory::create([
            'report_id'   => $rep2->id,
            'status_from' => null,
            'status_to'   => 'pending',
            'catatan'     => 'Laporan dikirim oleh pelapor.',
            'changed_by'  => 'Siti Nurhaliza (Pelapor)',
            'created_at'  => now()->subHours(5),
        ]);
    }
}
