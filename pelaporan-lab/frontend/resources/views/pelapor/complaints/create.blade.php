<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Informasi Pelaporan Fasilitas Laboratorium Komputer</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CDN with Alpine JS fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col justify-between">

    <!-- Navbar Publik -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 leading-tight">LabReport</h1>
                    <p class="text-xs text-gray-500">Pelaporan Fasilitas Lab Komputer</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pelapor.track') }}" class="px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                    Lacak Status Tiket
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold bg-gray-900 text-white hover:bg-gray-800 rounded-lg shadow-sm transition">
                    Login Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-10 px-4 sm:px-6 lg:px-8 flex-1">
        <div class="max-w-3xl mx-auto">
            
            <!-- Hero Header -->
            <div class="text-center mb-8">
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wider">
                    Form Pelaporan Publik (Tanpa Login)
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-3">
                    Laporkan Kerusakan Fasilitas Lab
                </h2>
                <p class="text-sm text-gray-600 mt-2 max-w-xl mx-auto">
                    Ketik informasi kerusakan perangkat atau fasilitas lab komputer di bawah ini. Laporan Anda akan langsung diteruskan ke tim teknisi lab.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm">
                    <div class="font-bold">Berhasil!</div>
                    <div class="text-sm mt-0.5">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
                    <div class="font-bold">Perhatian!</div>
                    <div class="text-sm mt-0.5">{{ session('error') }}</div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
                <form method="POST" action="{{ route('pelapor.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- 1. Identitas Pelapor -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 sm:p-5">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                            1. Identitas Pelapor
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_pelapor" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_pelapor" name="nama_pelapor" required value="{{ old('nama_pelapor') }}" placeholder="Contoh: Ahmad Fauzi" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" />
                                @error('nama_pelapor')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kontak_pelapor" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Kontak (WhatsApp / Email) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="kontak_pelapor" name="kontak_pelapor" required value="{{ old('kontak_pelapor') }}" placeholder="08123456789 atau email@univ.ac.id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" />
                                @error('kontak_pelapor')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 2. Lokasi & Fasilitas -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 sm:p-5">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                            2. Lokasi & Perangkat yang Rusak
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Laboratorium -->
                            <div>
                                <label for="laboratory_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Pilih Laboratorium <span class="text-red-500">*</span>
                                </label>
                                <select id="laboratory_id" name="laboratory_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border bg-white">
                                    <option value="">-- Pilih Ruang Lab --</option>
                                    @foreach($laboratories as $lab)
                                        <option value="{{ $lab['id'] }}" {{ old('laboratory_id') == $lab['id'] ? 'selected' : '' }}>
                                            {{ $lab['nama_lab'] }} ({{ $lab['lokasi'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('laboratory_id')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Fasilitas -->
                            <div>
                                <label for="facility_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Fasilitas / Komponen <span class="text-red-500">*</span>
                                </label>
                                <select id="facility_id" name="facility_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border bg-white">
                                    <option value="">-- Pilih Fasilitas --</option>
                                    @foreach($facilities as $fac)
                                        <option value="{{ $fac['id'] }}" {{ old('facility_id') == $fac['id'] ? 'selected' : '' }}>
                                            {{ $fac['nama_fasilitas'] }} [{{ strtoupper($fac['kategori']) }}]
                                        </option>
                                    @endforeach
                                </select>
                                @error('facility_id')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor PC / Meja -->
                            <div class="sm:col-span-2">
                                <label for="nomor_pc" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Nomor PC / Posisi Meja <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" id="nomor_pc" name="nomor_pc" value="{{ old('nomor_pc') }}" placeholder="Contoh: PC-04, Meja Baris 2 Kiri, atau Switch Rack Utama" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" />
                            </div>
                        </div>
                    </div>

                    <!-- 3. Rincian Masalah -->
                    <div>
                        <label for="deskripsi_masalah" class="block text-sm font-semibold text-gray-700 mb-1">
                            Deskripsi Kerusakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4" required placeholder="Jelaskan secara detail gejala kerusakan (misal: layar bergaris, kabel tidak konek, PC bunyi beep saat dinyalakan, dsb)..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border">{{ old('deskripsi_masalah') }}</textarea>
                        @error('deskripsi_masalah')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 4. Foto Bukti Kerusakan -->
                    <div>
                        <label for="foto_bukti" class="block text-sm font-semibold text-gray-700 mb-1">
                            Foto Bukti Kerusakan <span class="text-xs font-normal text-gray-400">(Opsional, format JPG/PNG, maks. 5MB)</span>
                        </label>
                        <input type="file" id="foto_bukti" name="foto_bukti" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 rounded-lg p-1 bg-white" />
                        @error('foto_bukti')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-gray-500">
                            Setelah dikirim, Anda akan mendapatkan <strong>Kode Laporan</strong> untuk memantau proses perbaikan.
                        </div>

                        <button type="submit" class="w-full sm:w-auto px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-100 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span>Kirim Laporan Kerusakan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} LabReport &bull; Arsitektur Microservices &bull; Universitas Nurul Jadid
    </footer>

</body>
</html>
