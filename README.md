# Sistem Informasi Pelaporan Fasilitas Laboratorium Komputer (LabReport)
## Arsitektur Microservices Laravel

Project ini adalah implementasi sistem pelaporan fasilitas laboratorium komputer berbasis **Microservices Architecture** menggunakan framework Laravel, terbagi menjadi beberapa layanan independen dengan basis data terisolasi.

---

## 📁 Struktur Direktori Project

```text
Backend Micro/
├── pelaporan-lab/               # ARSITEKTUR UTAMA MICROSERVICES
│   ├── frontend/                # Port 8000 (UI/UX Pelapor & Admin)
│   ├── api-gateway/             # Port 8001 (Reverse Proxy & Routing)
│   ├── auth-service/            # Port 8002 (Autentikasi Admin, DB: db_auth)
│   ├── report-service/          # Port 8003 (Proses Bisnis Laporan, DB: db_report)
│   ├── docs/                    # Koleksi Postman API & Panduan Teknis
│   ├── tests/                   # Skrip Pengujian Otomatis End-to-End
│   ├── start-microservices.bat  # Script Peluncur Semua Layanan
│   └── stop-microservices.bat   # Script Penghenti Semua Layanan
│
├── arsip-monolith/              # CADANGAN ARSIP MONOLITH LAMA (Diisolasi)
│   ├── app/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   └── .env                     # Konfigurasi Monolith Lama (db_pengaduan_lab)
│
├── start-microservices.bat      # Shortcut Peluncur Layanan dari Root
├── stop-microservices.bat       # Shortcut Penghenti Layanan dari Root
└── README.md                    # Dokumentasi Proyek
```

---

## 🚀 Pembagian Layanan & Port

| Layanan | Direktori | Port | Database | Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| **Frontend Portal** | `pelaporan-lab/frontend` | `8000` | *None* | Portal Publik (Mahasiswa) & Dashboard Admin. Murni memanggil API Gateway. |
| **API Gateway** | `pelaporan-lab/api-gateway` | `8001` | *None* | Gerbang utama yang merutekan request ke service yang tepat. |
| **Auth Service** | `pelaporan-lab/auth-service` | `8002` | `db_auth` | Autentikasi Admin & penerbitan Bearer Token Sanctum. |
| **Report Service** | `pelaporan-lab/report-service` | `8003` | `db_report` | Layanan pelaporan kerusakan publik, QR Code unit lab, dan penugasan teknisi. |

---

## ⚡ Cara Menjalankan Sistem

1. Pastikan **MySQL di Laragon** sudah berjalan (Host: `127.0.0.1:3306`, User: `root`, Password kosong).
2. Database yang digunakan:
   - `db_auth` (untuk Auth Service)
   - `db_report` (untuk Report Service)
3. Cukup **double-click** file:
   ```cmd
   start-microservices.bat
   ```
   Keempat terminal layanan (`frontend`, `api-gateway`, `auth-service`, dan `report-service`) akan terbuka dan berjalan otomatis.
4. Buka browser:
   - **Portal Pelapor (Publik / Tanpa Login):** [http://127.0.0.1:8000/lapor](http://127.0.0.1:8000/lapor)
   - **Hasil & Lacak Laporan Publik:** [http://127.0.0.1:8000/lacak](http://127.0.0.1:8000/lacak)
   - **Login Administrator:** [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
     * *Email:* `admin@labreport.com`
     * *Password:* `password`
5. Untuk menghentikan semua layanan:
   ```cmd
   stop-microservices.bat
   ```

---

## 🛡️ Jaminan Privasi Pelapor & Fitur QR Code
* **Kerahasiaan Pelapor:** Nama dan kontak pelapor disamarkan secara otomatis di antarmuka publik demi menjaga privasi mahasiswa/pelapor.
* **Stiker QR Code Fasilitas:** Setiap unit komputer memiliki QR Code di halaman master fasilitas (`/admin/facilities`) yang dapat dicetak dan ditempel pada perangkat laboratorium untuk pelaporan cepat dengan deteksi lokasi otomatis.
