<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
            <a href="<?php echo e(route('pelapor.create')); ?>" class="flex items-center gap-3 group">
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

            <!-- Nav Actions: Menu untuk melihat hasil laporan (Tombol login admin dihapus) -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <a href="<?php echo e(route('pelapor.track')); ?>" class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md shadow-indigo-600/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Lihat Hasil Laporan</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-10 px-4 sm:px-6 lg:px-8 flex-1 animate-fade-in" x-data="{
        selectedCategory: '<?php echo e(request('kategori', '')); ?>',
        filterCategory(cat) {
            this.selectedCategory = (this.selectedCategory === cat) ? '' : cat;
        },
        charCount: 0,
        fileName: '',
        fileSize: '',
        filePreviewUrl: null,
        isSubmitting: false,
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
        <div class="max-w-3xl mx-auto space-y-8">

            <!-- Hero Banner -->
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-beacon"></span>
                    <span>Layanan Pengaduan Fasilitas Aktif &bull; Tanpa Perlu Login</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Laporkan Kerusakan Fasilitas Lab
                </h2>
                <p class="text-sm sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Sampaikan kendala komputer, jaringan, atau software laboratorium komputer. Laporan Anda langsung diteruskan kepada tim teknisi siaga.
                </p>

                <!-- 3 Card Panduan Kategori Kerusakan Interaktif (Klik untuk Filter Fasilitas) -->
                <div class="pt-2">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">
                        Pilih Kategori Kerusakan untuk Mempercepat Pengisian:
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 text-left">
                        <!-- Card 1: Tentang Software -->
                        <div @click="filterCategory('software')"
                            :class="selectedCategory === 'software' ? 'ring-2 ring-purple-600 bg-purple-50/60 border-purple-500 shadow-md scale-[1.02]' : 'bg-white hover:scale-[1.01] hover:shadow-md border-purple-200/80'"
                            class="p-4 rounded-2xl border transition-all text-left flex flex-col justify-between cursor-pointer border-t-4 border-t-purple-600 group relative">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                    </div>
                                    <span x-show="selectedCategory === 'software'" x-cloak class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-600 text-white">
                                        Aktif
                                    </span>
                                </div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-purple-700">1. Tentang Software</h4>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                    Aplikasi crash, lisensi software habis, sistem operasi (OS) bermasalah, atau terkena virus.
                                </p>
                            </div>
                            <div class="mt-3 pt-2 border-t border-purple-100/60 text-[10px] font-bold text-purple-600 flex items-center justify-between">
                                <span x-text="selectedCategory === 'software' ? 'Kategori Terpilih' : 'Klik untuk Pilih'">Klik untuk Pilih</span>
                                <span>&rarr;</span>
                            </div>
                        </div>

                        <!-- Card 2: Tentang Hardware -->
                        <div @click="filterCategory('hardware')"
                            :class="selectedCategory === 'hardware' ? 'ring-2 ring-blue-600 bg-blue-50/60 border-blue-500 shadow-md scale-[1.02]' : 'bg-white hover:scale-[1.01] hover:shadow-md border-blue-200/80'"
                            class="p-4 rounded-2xl border transition-all text-left flex flex-col justify-between cursor-pointer border-t-4 border-t-blue-600 group relative">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span x-show="selectedCategory === 'hardware'" x-cloak class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-600 text-white">
                                        Aktif
                                    </span>
                                </div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-blue-700">2. Tentang Hardware</h4>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                    Unit PC mati, layar monitor bergaris/blank, mouse/keyboard tidak terdeteksi, power supply.
                                </p>
                            </div>
                            <div class="mt-3 pt-2 border-t border-blue-100/60 text-[10px] font-bold text-blue-600 flex items-center justify-between">
                                <span x-text="selectedCategory === 'hardware' ? 'Kategori Terpilih' : 'Klik untuk Pilih'">Klik untuk Pilih</span>
                                <span>&rarr;</span>
                            </div>
                        </div>

                        <!-- Card 3: Tentang Jaringan -->
                        <div @click="filterCategory('jaringan')"
                            :class="selectedCategory === 'jaringan' ? 'ring-2 ring-emerald-600 bg-emerald-50/60 border-emerald-500 shadow-md scale-[1.02]' : 'bg-white hover:scale-[1.01] hover:shadow-md border-emerald-200/80'"
                            class="p-4 rounded-2xl border transition-all text-left flex flex-col justify-between cursor-pointer border-t-4 border-t-emerald-600 group relative">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                        </svg>
                                    </div>
                                    <span x-show="selectedCategory === 'jaringan'" x-cloak class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-600 text-white">
                                        Aktif
                                    </span>
                                </div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700">3. Tentang Jaringan</h4>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                    Koneksi LAN putus, kabel lepas, akses internet lambat, IP conflict, switch/router lab.
                                </p>
                            </div>
                            <div class="mt-3 pt-2 border-t border-emerald-100/60 text-[10px] font-bold text-emerald-600 flex items-center justify-between">
                                <span x-text="selectedCategory === 'jaringan' ? 'Kategori Terpilih' : 'Klik untuk Pilih'">Klik untuk Pilih</span>
                                <span>&rarr;</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notifikasi Filter Kategori Aktif -->
                    <div x-show="selectedCategory" x-cloak class="mt-3 p-3 bg-indigo-50 border border-indigo-200 rounded-2xl flex items-center justify-between text-xs text-indigo-900 animate-fade-in">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-beacon"></span>
                            <span>Filter Aktif: Menampilkan opsi fasilitas kategori <strong class="uppercase font-bold" x-text="selectedCategory"></strong></span>
                        </div>
                        <button type="button" @click="selectedCategory = ''" class="px-2.5 py-1 bg-white hover:bg-slate-100 text-indigo-700 font-bold rounded-lg border border-indigo-200 shadow-xs transition">
                            Tampilkan Semua
                        </button>
                    </div>
                </div>

                <!-- Jaminan Kerahasiaan Pelapor (Revisi Dosen: Utamakan Jaga Rahasia Pelapor) -->
                <div class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-left text-xs text-slate-600">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900">Jaminan Kerahasiaan Pelapor:</span>
                        Data nama dan kontak Anda dienkripsi serta dirahasiakan dari publik. Hanya admin dan teknisi yang memproses penanganan kendala ini.
                    </div>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="p-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-2xl shadow-sm space-y-1">
                    <div class="font-bold flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Laporan Anda Berhasil Terkirim!</span>
                    </div>
                    <div class="text-xs text-emerald-800 leading-relaxed pl-7"><?php echo e(session('success')); ?></div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="p-5 bg-rose-50 border-l-4 border-rose-500 text-rose-900 rounded-2xl shadow-sm space-y-1">
                    <div class="font-bold flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Terdapat Kendala</span>
                    </div>
                    <div class="text-xs text-rose-800 leading-relaxed pl-7"><?php echo e(session('error')); ?></div>
                </div>
            <?php endif; ?>

            <!-- Form Card Utama -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base">Formulir Laporan Baru</h3>
                        <p class="text-xs text-slate-300">Isi data di bawah dengan lengkap dan jelas</p>
                    </div>
                    <span class="text-xs font-mono font-bold bg-white/10 px-3 py-1 rounded-full text-indigo-200 border border-white/10">
                        Langkah 1 s/d 3
                    </span>
                </div>

                <form method="POST" action="<?php echo e(route('pelapor.store')); ?>" enctype="multipart/form-data" @submit="isSubmitting = true" class="p-6 sm:p-8 space-y-7">
                    <?php echo csrf_field(); ?>

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
                                    <input type="text" id="nama_pelapor" name="nama_pelapor" required value="<?php echo e(old('nama_pelapor')); ?>" placeholder="Contoh: Ahmad Fauzi" autocomplete="name" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition" />
                                </div>
                                <?php $__errorArgs = ['nama_pelapor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-rose-600 mt-1 font-medium"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                    <input type="text" id="kontak_pelapor" name="kontak_pelapor" required value="<?php echo e(old('kontak_pelapor')); ?>" placeholder="08123456789 atau email@univ.ac.id" autocomplete="email" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition" />
                                </div>
                                <?php $__errorArgs = ['kontak_pelapor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-rose-600 mt-1 font-medium"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100"></div>

                    <!-- SECTION 2: Lokasi & Perangkat -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs">2</span>
                                <span>Lokasi Laboratorium & Fasilitas</span>
                            </div>
                            <?php if(request('facility_id') || request('lab_id')): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Unit Terdeteksi via QR Code
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if(request('facility_id') || request('lab_id')): ?>
                            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-900 text-xs">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                <div>
                                    <strong>Unit QR Code Terdeteksi Otomatis:</strong> Data ruangan dan perangkat telah otomatis terpilih sesuai stiker barcode unit yang Anda scan.
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Laboratorium -->
                            <div>
                                <label for="laboratory_id" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Pilih Ruang Lab <span class="text-rose-500">*</span>
                                </label>
                                <select id="laboratory_id" name="laboratory_id" required class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 bg-white transition">
                                    <option value="">-- Pilih Laboratorium --</option>
                                    <?php $__currentLoopData = $laboratories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($lab['id']); ?>" <?php echo e(old('laboratory_id', request('lab_id')) == $lab['id'] ? 'selected' : ''); ?>>
                                            <?php echo e($lab['nama_lab']); ?> (<?php echo e($lab['lokasi']); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['laboratory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-rose-600 mt-1 font-medium"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Fasilitas dengan filter kategori reaktif -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="facility_id" class="block text-xs font-bold text-slate-700 uppercase">
                                        Fasilitas / Komponen <span class="text-rose-500">*</span>
                                    </label>
                                    <span x-show="selectedCategory" x-cloak class="text-[10px] text-indigo-600 font-bold uppercase" x-text="'Filter: ' + selectedCategory"></span>
                                </div>
                                <select id="facility_id" name="facility_id" required class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 bg-white transition">
                                    <option value="">-- Pilih Fasilitas --</option>
                                    <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($fac['id']); ?>" 
                                            x-show="!selectedCategory || selectedCategory === '<?php echo e(strtolower($fac['kategori'])); ?>'"
                                            <?php echo e(old('facility_id', request('facility_id')) == $fac['id'] ? 'selected' : ''); ?>>
                                            <?php echo e($fac['nama_fasilitas']); ?> &mdash; [<?php echo e(strtoupper($fac['kategori'])); ?>]
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-rose-600 mt-1 font-medium"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Nomor PC / Meja -->
                            <div class="sm:col-span-2">
                                <label for="nomor_pc" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nomor PC / Posisi Meja <span class="text-slate-400 font-normal normal-case">(Opsional / Sesuai Stiker Unit)</span>
                                </label>
                                <input type="text" id="nomor_pc" name="nomor_pc" value="<?php echo e(old('nomor_pc', request('pc'))); ?>" placeholder="Contoh: PC-04, Meja Baris 3 Sebelah Kiri, atau Switch Rack Utama" class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition" />
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
                                <span class="text-[11px] font-mono font-semibold" :class="charCount > 1800 ? 'text-amber-600' : 'text-slate-400'" x-text="charCount + ' / 2000 karakter'">0 / 2000 karakter</span>
                            </div>
                            <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4" required maxlength="2000" x-on:input="charCount = $el.value.length" placeholder="Jelaskan secara rinci kendala yang dialami (misal: layar bergaris, kabel LAN longgar, tombol power macet, OS error saat boot)..." class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 leading-relaxed transition"><?php echo e(old('deskripsi_masalah')); ?></textarea>
                            <?php $__errorArgs = ['deskripsi_masalah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-rose-600 mt-1 font-medium"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Foto Bukti Upload dengan Live Preview Interaktif -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Foto Bukti Kerusakan <span class="text-slate-400 font-normal normal-case">(Opsional, JPG/PNG, maks. 5MB)</span>
                            </label>

                            <!-- Area Dropzone / Input -->
                            <div class="border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-6 text-center cursor-pointer transition-all bg-slate-50/50 hover:bg-indigo-50/20 group" x-on:click="$refs.fileInput.click()" x-show="!filePreviewUrl">
                                <input type="file" id="foto_bukti" name="foto_bukti" accept="image/jpeg,image/png,image/jpg" class="hidden" x-ref="fileInput" x-on:change="handleFileSelect($event)" />
                                
                                <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform shadow-xs">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-bold text-slate-800">
                                    Klik untuk memilih foto bukti kerusakan
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Mendukung format PNG, JPG, atau JPEG hingga 5MB</p>
                            </div>

                            <!-- Live Preview Area jika file dipilih -->
                            <div x-show="filePreviewUrl" x-cloak class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-4 animate-fade-in shadow-xs">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <template x-if="filePreviewUrl">
                                        <img :src="filePreviewUrl" class="w-16 h-16 object-cover rounded-xl border border-slate-200 shrink-0 shadow-xs" />
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

                            <?php $__errorArgs = ['foto_bukti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-rose-600 mt-1 font-medium"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Submit Button Area -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-slate-500 leading-relaxed text-center sm:text-left">
                            Setelah dikirim, Anda akan langsung menerima <strong>Kode Tiket</strong> untuk melacak proses perbaikan.
                        </div>

                        <button type="submit" :disabled="isSubmitting" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 active:scale-95 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/25 transition-all flex items-center justify-center gap-2 text-sm disabled:opacity-60 cursor-pointer">
                            <span x-show="!isSubmitting" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span>Kirim Laporan Kerusakan</span>
                            </span>
                            <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Mengirim Laporan...</span>
                            </span>
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
                <a href="<?php echo e(route('pelapor.track')); ?>" class="px-4 py-2 bg-white text-indigo-600 hover:text-indigo-800 font-bold rounded-xl border border-indigo-200 shrink-0 transition">
                    Cek Status Sekarang &rarr;
                </a>
            </div>

        </div>
    </main>

    <!-- Footer Publik -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                &copy; <?php echo e(date('Y')); ?> <strong>LabReport</strong> &bull; Universitas Nurul Jadid
            </div>
            <div class="text-slate-400">
                Arsitektur Terdistribusi Microservices &bull; Laravel 12 & MySQL
            </div>
        </div>
    </footer>

</body>
</html>
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views/pelapor/complaints/create.blade.php ENDPATH**/ ?>