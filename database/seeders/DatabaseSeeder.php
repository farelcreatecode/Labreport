<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Facility;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (1 Admin, 2 Teknisi, 3 Mahasiswa, 1 Dosen)
        $password = Hash::make('password');

        $admin = User::create([
            'name' => 'Administrator Lab',
            'email' => 'admin@labreport.com',
            'nim_nip' => 'ADM001',
            'role' => 'admin',
            'password' => $password,
        ]);

        $teknisi1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.teknisi@labreport.com',
            'nim_nip' => 'TEK001',
            'role' => 'teknisi',
            'password' => $password,
        ]);

        $teknisi2 = User::create([
            'name' => 'Agus Pratama',
            'email' => 'agus.teknisi@labreport.com',
            'nim_nip' => 'TEK002',
            'role' => 'teknisi',
            'password' => $password,
        ]);

        $mhs1 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@student.ac.id',
            'nim_nip' => '2101001',
            'role' => 'mahasiswa',
            'password' => $password,
        ]);

        $mhs2 = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti.rahma@student.ac.id',
            'nim_nip' => '2101002',
            'role' => 'mahasiswa',
            'password' => $password,
        ]);

        $mhs3 = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'rian.hidayat@student.ac.id',
            'nim_nip' => '2101003',
            'role' => 'mahasiswa',
            'password' => $password,
        ]);

        $dosen = User::create([
            'name' => 'Dr. Hendra Wijaya, M.Kom',
            'email' => 'hendra.wijaya@univ.ac.id',
            'nim_nip' => '198501152010121001',
            'role' => 'dosen',
            'password' => $password,
        ]);

        // 2. Seed Labs (3 Lab)
        $lab1 = Lab::create([
            'nama_lab' => 'Lab Komputer Dasar',
            'lokasi' => 'Gedung A Lantai 2',
            'kepala_lab' => 'Dr. Hendra Wijaya, M.Kom',
        ]);

        $lab2 = Lab::create([
            'nama_lab' => 'Lab Rekayasa Perangkat Lunak',
            'lokasi' => 'Gedung B Lantai 3',
            'kepala_lab' => 'Ir. Ratna Dewi, M.T',
        ]);

        $lab3 = Lab::create([
            'nama_lab' => 'Lab Jaringan & Cyber Security',
            'lokasi' => 'Gedung C Lantai 1',
            'kepala_lab' => 'Bambang Eko, S.Kom., M.Cs',
        ]);

        // 3. Seed Categories (Hardware, Software, Jaringan)
        $catHardware = Category::create(['nama_kategori' => 'Hardware']);
        $catSoftware = Category::create(['nama_kategori' => 'Software']);
        $catJaringan = Category::create(['nama_kategori' => 'Jaringan']);

        // 4. Seed Facilities dengan kondisi campuran
        $facilitiesData = [
            // Lab 1
            ['lab_id' => $lab1->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'PC Client 01 - 15', 'kondisi' => 'baik'],
            ['lab_id' => $lab1->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'PC Client 16 (Motherboard bermasalah)', 'kondisi' => 'rusak'],
            ['lab_id' => $lab1->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'Monitor LG 24" Unit 05 (Flickering)', 'kondisi' => 'rusak'],
            ['lab_id' => $lab1->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'Keyboard & Mouse Logitech Lab 1', 'kondisi' => 'baik'],
            ['lab_id' => $lab1->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'Printer Epson L3110 (Paper Jam)', 'kondisi' => 'rusak'],
            ['lab_id' => $lab1->id, 'category_id' => $catSoftware->id, 'nama_fasilitas' => 'Windows 11 Education License Lab 1', 'kondisi' => 'baik'],
            ['lab_id' => $lab1->id, 'category_id' => $catSoftware->id, 'nama_fasilitas' => 'Dev C++ & Code::Blocks IDE', 'kondisi' => 'rusak'],
            ['lab_id' => $lab1->id, 'category_id' => $catJaringan->id, 'nama_fasilitas' => 'Switch D-Link 24 Port Lab 1', 'kondisi' => 'baik'],

            // Lab 2
            ['lab_id' => $lab2->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'PC Workstation RPL 01 - 20', 'kondisi' => 'baik'],
            ['lab_id' => $lab2->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'PC Workstation RPL 08 (RAM Bermasalah)', 'kondisi' => 'rusak'],
            ['lab_id' => $lab2->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'Monitor Dell 27" Lab RPL', 'kondisi' => 'baik'],
            ['lab_id' => $lab2->id, 'category_id' => $catSoftware->id, 'nama_fasilitas' => 'VS Code & Node.js Environment', 'kondisi' => 'baik'],
            ['lab_id' => $lab2->id, 'category_id' => $catSoftware->id, 'nama_fasilitas' => 'Laragon Local Server Suite', 'kondisi' => 'baik'],
            ['lab_id' => $lab2->id, 'category_id' => $catJaringan->id, 'nama_fasilitas' => 'Router Cisco Lab RPL', 'kondisi' => 'baik'],

            // Lab 3
            ['lab_id' => $lab3->id, 'category_id' => $catHardware->id, 'nama_fasilitas' => 'PC Jaringan 01 - 10', 'kondisi' => 'baik'],
            ['lab_id' => $lab3->id, 'category_id' => $catJaringan->id, 'nama_fasilitas' => 'Mikrotik RB750Gr3 Lab 3', 'kondisi' => 'baik'],
            ['lab_id' => $lab3->id, 'category_id' => $catJaringan->id, 'nama_fasilitas' => 'Access Point Ubiquiti Unifi (Port Mati)', 'kondisi' => 'rusak'],
            ['lab_id' => $lab3->id, 'category_id' => $catSoftware->id, 'nama_fasilitas' => 'Cisco Packet Tracer 8.2', 'kondisi' => 'baik'],
            ['lab_id' => $lab3->id, 'category_id' => $catSoftware->id, 'nama_fasilitas' => 'Wireshark Network Analyzer', 'kondisi' => 'baik'],
        ];

        foreach ($facilitiesData as $facility) {
            Facility::create($facility);
        }

        // 5. Seed 10 Complaints dengan status bervariasi
        $complaintsData = [
            [
                'user_id' => $mhs1->id,
                'lab_id' => $lab1->id,
                'category_id' => $catHardware->id,
                'technician_id' => null,
                'nomor_pc' => 'PC-04',
                'deskripsi_masalah' => 'PC tiba-tiba mati sendiri saat digunakan dan bau gosong pada power supply.',
                'foto_bukti' => null,
                'status' => 'pending',
                'tanggapan' => null,
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id' => $mhs2->id,
                'lab_id' => $lab1->id,
                'category_id' => $catHardware->id,
                'technician_id' => null,
                'nomor_pc' => 'PC-05',
                'deskripsi_masalah' => 'Layar monitor berkedip-kedip dan muncul garis-garis merah vertikal.',
                'foto_bukti' => null,
                'status' => 'pending',
                'tanggapan' => null,
                'created_at' => now()->subDays(4),
            ],
            [
                'user_id' => $dosen->id,
                'lab_id' => $lab2->id,
                'category_id' => $catJaringan->id,
                'technician_id' => null,
                'nomor_pc' => null,
                'deskripsi_masalah' => 'Koneksi internet di Lab RPL terputus total saat praktikum berlangsung.',
                'foto_bukti' => null,
                'status' => 'pending',
                'tanggapan' => null,
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $mhs1->id,
                'lab_id' => $lab2->id,
                'category_id' => $catSoftware->id,
                'technician_id' => $teknisi1->id,
                'nomor_pc' => 'PC-08',
                'deskripsi_masalah' => 'Layar biru (BSOD) kode CRITICAL_PROCESS_DIED saat booting Windows setelah update software.',
                'foto_bukti' => null,
                'status' => 'diproses',
                'tanggapan' => 'Sedang dilakukan repair instalasi OS Windows dan driver sistem.',
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $mhs3->id,
                'lab_id' => $lab1->id,
                'category_id' => $catHardware->id,
                'technician_id' => $teknisi2->id,
                'nomor_pc' => null,
                'deskripsi_masalah' => 'Kertas tersangkut di roller printer dan indikator tinta merah menyala terus.',
                'foto_bukti' => null,
                'status' => 'diproses',
                'tanggapan' => 'Komponen penarik kertas sedang dibersihkan dan direset.',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $dosen->id,
                'lab_id' => $lab3->id,
                'category_id' => $catJaringan->id,
                'technician_id' => $teknisi1->id,
                'nomor_pc' => null,
                'deskripsi_masalah' => 'Access Point Unifi tidak memancarkan sinyal SSID wifi lab.',
                'foto_bukti' => null,
                'status' => 'diproses',
                'tanggapan' => 'Kabel PoE sedang diganti dan adaptor diperiksa ulang.',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $mhs2->id,
                'lab_id' => $lab1->id,
                'category_id' => $catSoftware->id,
                'technician_id' => $teknisi1->id,
                'nomor_pc' => 'PC-11',
                'deskripsi_masalah' => 'Aplikasi Dev C++ error missing dll gcc compiler sehingga tidak bisa kompilasi praktikum.',
                'foto_bukti' => null,
                'status' => 'selesai',
                'tanggapan' => 'MinGW compiler dan path environment variables sudah diperbaiki.',
                'created_at' => now()->subDays(7),
            ],
            [
                'user_id' => $mhs3->id,
                'lab_id' => $lab2->id,
                'category_id' => $catHardware->id,
                'technician_id' => $teknisi2->id,
                'nomor_pc' => 'PC-14',
                'deskripsi_masalah' => 'Klik kiri mouse double-click sendiri sehingga menyulitkan navigasi.',
                'foto_bukti' => null,
                'status' => 'selesai',
                'tanggapan' => 'Mouse diganti dengan mouse Logitech baru.',
                'created_at' => now()->subDays(6),
            ],
            [
                'user_id' => $dosen->id,
                'lab_id' => $lab1->id,
                'category_id' => $catHardware->id,
                'technician_id' => $teknisi1->id,
                'nomor_pc' => 'PC-02',
                'deskripsi_masalah' => 'Kipas power supply mengeluarkan suara bising mendengung sangat keras.',
                'foto_bukti' => null,
                'status' => 'selesai',
                'tanggapan' => 'Power Supply unit telah diganti dan debu di dalam heatsink dibersihkan.',
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id' => $mhs1->id,
                'lab_id' => $lab3->id,
                'category_id' => $catSoftware->id,
                'technician_id' => $teknisi2->id,
                'nomor_pc' => 'PC-01',
                'deskripsi_masalah' => 'Mohon install game Valorant di PC Lab untuk kompetisi esport kampus.',
                'foto_bukti' => null,
                'status' => 'ditolak',
                'tanggapan' => 'Laporan ditolak. PC Lab dilarang diinstall software game di luar silabus praktikum akademik.',
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($complaintsData as $complaint) {
            Complaint::create($complaint);
        }
    }
}
