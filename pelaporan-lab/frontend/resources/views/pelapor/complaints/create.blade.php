<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Informasi Pelaporan Fasilitas Laboratorium Komputer</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeInUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.25); }
        }
        .animate-beacon {
            animation: pulseGlow 2s infinite ease-in-out;
        }

        /* Subtle focus glow */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
    </style>
</head>
<body class="font-sans bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">

    <!-- Navbar Publik Modern -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <a href="{{ route('pelapor.create') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>LabReport</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">Portal Publik</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">Universitas Nurul Jadid</p>
                </div>
            </a>

            <!-- Nav Actions -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <a href="{{ route('pelapor.track') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:text-indigo-600 hover:bg-indigo-50/60 rounded-xl transition">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Lacak Status Tiket</span>
                </a>

                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs sm:text-sm font-bold bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-md shadow-slate-900/10 transition">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Login Admin</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-10 px-4 sm:px-6 lg:px-8 flex-1 animate-fade-in">
        <div class="max-w-3xl mx-auto space-y-8">

            <!-- Hero Banner -->
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-beacon"></span>
                    <span>Layanan Pengaduan Aktif 24/7 &bull; Tanpa Perlu Login</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Laporkan Kerusakan Fasilitas Lab
                </h2>
                <p class="text-sm sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Sampaikan kendala komputer, jaringan, atau software laboratorium komputer. Laporan Anda langsung diteruskan kepada teknisi bertugas.
                </p>

                <!-- Quick Highlights -->
                <div class="grid grid-cols-3 gap-3 pt-2 max-w-lg mx-auto">
                    <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs text-center">
                        <div class="text-xs font-bold text-slate-400 uppercase">Ruang Lab</div>
                        <div class="text-base font-extrabold text-slate-900 mt-0.5">3 Laboratorium</div>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs text-center">
                        <div class="text-xs font-bold text-slate-400 uppercase">Petugas</div>
                        <div class="text-base font-extrabold text-indigo-600 mt-0.5">Teknisi Siaga</div>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs text-center">
                        <div class="text-xs font-bold text-slate-400 uppercase">Kode Tiket</div>
                        <div class="text-base font-extrabold text-emerald-600 mt-0.5">Otomatis</div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="p-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-2xl shadow-sm space-y-1">
                    <div class="font-bold flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Laporan Anda Berhasil Terkirim!</span>
                    </div>
                    <div class="text-xs text-emerald-800 leading-relaxed pl-7">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-5 bg-rose-50 border-l-4 border-rose-500 text-rose-900 rounded-2xl shadow-sm space-y-1">
                    <div class="font-bold flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Terdapat Kendala</span>
                    </div>
                    <div class="text-xs text-rose-800 leading-relaxed pl-7">{{ session('error') }}</div>
                </div>
            @endif

            <!-- Form Card Utama -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden" x-data="{
                charCount: 0,
                fileName: '',
                fileSize: '',
                filePreviewUrl: null,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                        this.fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                        this.filePreviewUrl = URL.createObjectURL(file);
                    } else {
                        this.removeFile();
                    }
                },
                removeFile() {
                    this.fileName = '';
                    this.fileSize = '';
                    this.filePreviewUrl = null;
                    this.$refs.fileInput.value = '';
                }
            }">
                <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-indigo-950 text-white flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base">Formulir Laporan Baru</h3>
                        <p class="text-xs text-slate-300">Isi data di bawah dengan lengkap dan jelas</p>
                    </div>
                    <span class="text-xs font-mono font-bold bg-white/10 px-3 py-1 rounded-full text-indigo-200 border border-white/10">
                        Langkah 1 s/d 3
                    </span>
                </div>

                <form method="POST" action="{{ route('pelapor.store') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-7">
                    @csrf

                    <!-- SECTION 1: Identitas Pelapor -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-indigo-700 uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs">1</span>
                            <span>Identitas Pelapor</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_pelapor" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="nama_pelapor" name="nama_pelapor" required value="{{ old('nama_pelapor') }}" placeholder="Contoh: Ahmad Fauzi" autocomplete="name" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 transition" />
                                </div>
                                @error('nama_pelapor')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kontak_pelapor" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kontak (No. WA / Email) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="kontak_pelapor" name="kontak_pelapor" required value="{{ old('kontak_pelapor') }}" placeholder="08123456789 atau email@univ.ac.id" autocomplete="email" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 transition" />
                                </div>
                                @error('kontak_pelapor')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100"></div>

                    <!-- SECTION 2: Lokasi & Perangkat -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-indigo-700 uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs">2</span>
                            <span>Lokasi Laboratorium & Fasilitas</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Laboratorium -->
                            <div>
                                <label for="laboratory_id" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Pilih Ruang Lab <span class="text-rose-500">*</span>
                                </label>
                                <select id="laboratory_id" name="laboratory_id" required class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 bg-white transition">
                                    <option value="">-- Pilih Laboratorium --</option>
                                    @foreach($laboratories as $lab)
                                        <option value="{{ $lab['id'] }}" {{ old('laboratory_id') == $lab['id'] ? 'selected' : '' }}>
                                            {{ $lab['nama_lab'] }} ({{ $lab['lokasi'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('laboratory_id')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fasilitas -->
                            <div>
                                <label for="facility_id" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Fasilitas / Komponen <span class="text-rose-500">*</span>
                                </label>
                                <select id="facility_id" name="facility_id" required class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 bg-white transition">
                                    <option value="">-- Pilih Fasilitas --</option>
                                    @foreach($facilities as $fac)
                                        <option value="{{ $fac['id'] }}" {{ old('facility_id') == $fac['id'] ? 'selected' : '' }}>
                                            {{ $fac['nama_fasilitas'] }} &mdash; [{{ strtoupper($fac['kategori']) }}]
                                        </option>
                                    @endforeach
                                </select>
                                @error('facility_id')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor PC / Meja -->
                            <div class="sm:col-span-2">
                                <label for="nomor_pc" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nomor PC / Posisi Meja <span class="text-slate-400 font-normal normal-case">(Opsional)</span>
                                </label>
                                <input type="text" id="nomor_pc" name="nomor_pc" value="{{ old('nomor_pc') }}" placeholder="Contoh: PC-04, Meja Baris 3 Sebelah Kiri, atau Switch Rack Utama" class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 transition" />
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100"></div>

                    <!-- SECTION 3: Rincian Kerusakan & Bukti -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-indigo-700 uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs">3</span>
                            <span>Rincian Kerusakan & Bukti Fisik</span>
                        </div>

                        <!-- Deskripsi Kerusakan -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="deskripsi_masalah" class="block text-xs font-bold text-slate-700 uppercase">
                                    Deskripsi Masalah <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-slate-400 font-mono" x-text="charCount + ' karakter'">0 karakter</span>
                            </div>
                            <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4" required x-on:input="charCount = $el.value.length" placeholder="Jelaskan secara rinci kendala yang dialami (misal: layar bergaris, kabel LAN longgar, tombol power macet, OS error saat boot)..." class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 leading-relaxed transition">{{ old('deskripsi_masalah') }}</textarea>
                            @error('deskripsi_masalah')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Bukti Upload dengan Live Preview Interaktif -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Foto Bukti Kerusakan <span class="text-slate-400 font-normal normal-case">(Opsional, JPG/PNG, maks. 5MB)</span>
                            </label>

                            <!-- Area Dropzone / Input -->
                            <div class="border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-6 text-center cursor-pointer transition bg-slate-50/50 hover:bg-indigo-50/20" x-on:click="$refs.fileInput.click()" x-show="!filePreviewUrl">
                                <input type="file" id="foto_bukti" name="foto_bukti" accept="image/jpeg,image/png,image/jpg" class="hidden" x-ref="fileInput" x-on:change="handleFileSelect($event)" />
                                
                                <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">
                                    Klik untuk memilih foto kerusakan
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Mendukung format PNG, JPG, atau JPEG hingga 5MB</p>
                            </div>

                            <!-- Live Preview Area jika file dipilih -->
                            <div x-show="filePreviewUrl" x-cloak class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <template x-if="filePreviewUrl">
                                        <img :src="filePreviewUrl" class="w-16 h-16 object-cover rounded-xl border border-slate-200 shrink-0" />
                                    </template>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate" x-text="fileName"></p>
                                        <p class="text-xs text-slate-400 font-mono" x-text="fileSize"></p>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Siap diunggah
                                        </span>
                                    </div>
                                </div>

                                <button type="button" x-on:click="removeFile()" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition text-xs font-semibold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </div>

                            @error('foto_bukti')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button Area -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-slate-500 leading-relaxed text-center sm:text-left">
                            Setelah dikirim, Anda akan langsung menerima <strong>Kode Tiket</strong> untuk melacak proses perbaikan.
                        </div>

                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/25 transition-all hover:scale-[1.02] flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span>Kirim Laporan Kerusakan</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Bantuan / Info -->
            <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-5 text-xs text-indigo-900 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold">Sudah pernah mengirim laporan?</span>
                        <p class="text-slate-500">Anda dapat memeriksa progres penanganan menggunakan kode laporan yang telah didapatkan.</p>
                    </div>
                </div>
                <a href="{{ route('pelapor.track') }}" class="px-4 py-2 bg-white text-indigo-600 hover:text-indigo-800 font-bold rounded-xl border border-indigo-200 shrink-0 transition">
                    Cek Status Sekarang &rarr;
                </a>
            </div>

        </div>
    </main>

    <!-- Footer Publik -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} <strong>LabReport</strong> &bull; Universitas Nurul Jadid
            </div>
            <div class="text-slate-400">
                Arsitektur Terdistribusi Microservices &bull; Laravel 12 & MySQL
            </div>
        </div>
    </footer>

</body>
</html>
