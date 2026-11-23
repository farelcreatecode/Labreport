# Sistem Informasi Pelaporan Fasilitas Laboratorium Komputer (LabReport)
## Arsitektur Microservices Laravel

Project ini adalah implementasi sistem pelaporan fasilitas laboratorium komputer berbasis **Microservices Architecture** menggunakan framework Laravel, terbagi menjadi beberapa layanan independen dengan basis data terisolasi dan portal antarmuka terpisah (*Separated Frontends / Micro-Frontends*).

---

## 📁 Struktur Direktori Project

```text
Backend Micro/
├── pelaporan-lab/               # ARSITEKTUR UTAMA MICROSERVICES
│   ├── frontend-pelapor/        # Port 8000 (Portal Publik: Form & Pelacakan Tiket Mahasiswa)
│   ├── frontend-admin/          # Port 8004 (Portal Backoffice: Login, Dashboard, & Notifikasi)
│   ├── api-gateway/             # Port 8001 (Reverse Proxy & Routing Terpusat)
│   ├── auth-service/            # Port 8002 (Autentikasi Admin, DB: db_auth)
│   ├── report-service/          # Port 8003 (Proses Bisnis Laporan, DB: db_report)
│   ├── notification-service/    # Port 8005 (Layanan Notifikasi Otomatis, DB: db_notification)
│   ├── docs/                    # Koleksi Postman API & Panduan Teknis
│   ├── tests/                   # Skrip Pengujian Otomatis End-to-End
│   ├── start-microservices.bat  # Script Peluncur 6 Layanan Sekaligus
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
| **Portal Pelapor (Publik)** | `pelaporan-lab/frontend-pelapor` | `8000` | *None* | Portal Publik khusus Mahasiswa/Pelapor tanpa login. Form pengaduan & lacak status. |
| **Portal Admin (Backoffice)** | `pelaporan-lab/frontend-admin` | `8004` | *None* | Portal khusus Admin Lab. Wajib login kredensial & Bearer Token. |
| **API Gateway** | `pelaporan-lab/api-gateway` | `8001` | *None* | Gerbang utama yang merutekan request dari kedua frontend ke microservice terkait. |
| **Auth Service** | `pelaporan-lab/auth-service` | `8002` | `db_auth` | Layanan autentikasi admin, validasi akun, & penerbitan Bearer Token Sanctum. |
| **Report Service** | `pelaporan-lab/report-service` | `8003` | `db_report` | Layanan pengaduan, upload foto bukti, QR Code fasilitas lab, & penugasan teknisi. |
| **Notification Service** | `pelaporan-lab/notification-service` | `8005` | `db_notification` | Notifikasi otomatis ke Mahasiswa saat status berubah, Teknisi saat ada tugas baru, & Admin saat laporan masuk. |

---

## ⚡ Cara Menjalankan Sistem

1. Pastikan **MySQL di Laragon** sudah berjalan (Host: `127.0.0.1:3306`, User: `root`, Password kosong).
2. Tiga Database yang digunakan:
   - `db_auth` (untuk Auth Service)
   - `db_report` (untuk Report Service)
   - `db_notification` (untuk Notification Service)
3. Cukup **double-click** file:
   ```cmd
   start-microservices.bat
   ```
   Seluruh 6 terminal layanan (`frontend-pelapor`, `frontend-admin`, `api-gateway`, `auth-service`, `report-service`, dan `notification-service`) akan terbuka dan berjalan otomatis.
4. Buka browser:
   - **Portal Pelapor (Publik / Tanpa Login):** [http://127.0.0.1:8000/lapor](http://127.0.0.1:8000/lapor)
   - **Hasil & Lacak Laporan Publik:** [http://127.0.0.1:8000/lacak](http://127.0.0.1:8000/lacak)
   - **Portal Admin (Login & Dashboard):** [http://127.0.0.1:8004/login](http://127.0.0.1:8004/login)
     - *Email:* `admin@labreport.com`
     - *Password:* `password`

---

## 🛑 Cara Menghentikan Sistem

Cukup **double-click** file:
```cmd
stop-microservices.bat
```
Semua proses PHP server di port 8000, 8001, 8002, 8003, 8004, dan 8005 akan dihentikan secara bersih.
