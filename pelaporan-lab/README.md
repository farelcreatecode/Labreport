# LabReport &mdash; Arsitektur Microservices Sistem Pelaporan Fasilitas Lab

Website Sistem Informasi Pelaporan Fasilitas Laboratorium Komputer berbasis **Microservices Architecture** menggunakan Laravel 12 dan MySQL.

---

## 🏛️ Struktur Arsitektur Sistem

Sistem ini dipecah menjadi **4 aplikasi mandiri**:

```
pelaporan-lab/
├── frontend/        -> Portal Web Pelapor & Dashboard Admin (Port: 8000)
├── api-gateway/     -> Router Tunggal & Reverse Proxy (Port: 8001)
├── auth-service/    -> Microservice Autentikasi Admin (Port: 8002, DB: db_auth)
├── report-service/  -> Microservice Laporan, Fasilitas & Teknisi (Port: 8003, DB: db_report)
├── docs/            -> Postman Collection & Dokumentasi API
├── tests/           -> End-to-End Test Script
├── start-microservices.bat -> Script Otomatis Menjalankan Seluruh Service
└── stop-microservices.bat  -> Script Menghentikan Seluruh Service
```

---

## 👥 Pengguna & Hak Akses

1. **Pelapor (Publik / Tanpa Login):**
   - Mengisi form pelaporan langsung di `http://127.0.0.1:8000/`.
   - Mengisi nama, kontak (WhatsApp/Email), memilih laboratorium, fasilitas, nomor PC, deskripsi kerusakan, dan upload foto bukti.
   - Mendapatkan **Kode Laporan** (contoh: `#LR-202611-0001`) dan dapat melacak status perbaikan secara langsung di menu *Lacak Status Tiket*.

2. **Admin (Wajib Login):**
   - Login di `http://127.0.0.1:8000/login`.
   - Memantau dashboard statistik (Software, Hardware, Jaringan) dan status laporan.
   - Menugaskan teknisi lapangan ke setiap laporan.
   - Memperbarui status laporan (`pending` &rarr; `diproses` &rarr; `selesai` / `ditolak`).
   - Melihat visualisasi timeline riwayat penanganan laporan.
   - Mengelola master data Laboratorium, Fasilitas, dan Teknisi Lapangan.

3. **Teknisi (Bukan User Login, Pekerja Lapangan):**
   - Tidak memiliki akun login sistem.
   - Data teknisi disimpan di database `db_report` (tabel `technicians`) dan dipilih oleh Admin via dropdown penugasan.

---

## 💾 Ketentuan Database (MySQL)

Sistem menggunakan **2 Database terpisah**:
- **`db_auth`** (Auth Service):
  - `users` (Akun Administrator)
  - `personal_access_tokens` (Sanctum Bearer Token)
- **`db_report`** (Report Service):
  - `laboratories` (Data Lab)
  - `facilities` (Data Fasilitas & Unit PC)
  - `technicians` (Data Teknisi Lapangan)
  - `reports` (Data Laporan Kerusakan)
  - `report_attachments` (Foto Bukti Kerusakan)
  - `report_histories` (Audit Trail Riwayat Penanganan)

---

## 🚀 Cara Menjalankan Sistem

### Cara 1: Menggunakan Script Otomatis (Sangat Mudah)
Cukup double-click file:
`c:\laragon\www\Backend Micro\pelaporan-lab\start-microservices.bat`
Script ini akan langsung membuka 4 server secara bersamaan.

### Cara 2: Manual via Terminal (4 Terminal Berbeda)

```bash
# Terminal 1: Auth Service (Port 8002)
cd pelaporan-lab/auth-service
php artisan serve --port=8002

# Terminal 2: Report Service (Port 8003)
cd pelaporan-lab/report-service
php artisan serve --port=8003

# Terminal 3: API Gateway (Port 8001)
cd pelaporan-lab/api-gateway
php artisan serve --port=8001

# Terminal 4: Frontend Portal (Port 8000)
cd pelaporan-lab/frontend
php artisan serve --port=8000
```

---

## 🔑 Kredensial Login Administrator

- **URL Login:** `http://127.0.0.1:8000/login`
- **Email:** `admin@labreport.com`
- **Password:** `password`

---

## 📬 Pengujian API Menggunakan Postman

File koleksi Postman siap pakai tersedia di:
`pelaporan-lab/docs/LabReport_Microservices.postman_collection.json`

Tinggal import file tersebut ke Postman untuk menguji endpoint:
1. `GET /api/public/form-data`
2. `POST /api/public/reports`
3. `POST /api/auth/login`
4. `GET /api/admin/dashboard`
5. `PUT /api/admin/reports/{id}/status`
6. `GET /api/admin/technicians`
