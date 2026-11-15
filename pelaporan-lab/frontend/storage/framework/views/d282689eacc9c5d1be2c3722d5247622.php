<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Status Tiket &mdash; LabReport</title>

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
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.25); }
        }
        .animate-beacon {
            animation: pulseGlow 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="font-sans bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white" x-data="{
    copied: false,
    copyCode(text) {
        navigator.clipboard.writeText(text);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }
}">

    <!-- Header Publik -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <a href="<?php echo e(route('pelapor.create')); ?>" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>LabReport</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">Portal Publik</span>
                    </h1>
                    <p class="text-xs text-slate-400">Hasil Penanganan & Pelacakan Tiket</p>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('pelapor.create')); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs sm:text-sm font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Buat Laporan Baru</span>
                </a>
            </div>
        </div>
    </header>

    <main class="py-10 px-4 sm:px-6 lg:px-8 flex-1 animate-fade-in">
        <div class="max-w-3xl mx-auto space-y-7">

            <!-- Search Box Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Lacak Status Penanganan Tiket</h2>
                        <p class="text-xs text-slate-400">Masukkan kode registrasi laporan untuk melihat progres teknisi</p>
                    </div>
                </div>

                <form method="GET" action="<?php echo e(route('pelapor.track')); ?>" class="flex flex-col sm:flex-row gap-2.5 pt-2">
                    <div class="relative flex-1">
                        <input type="text" name="kode" value="<?php echo e($kode); ?>" required placeholder="Contoh: #LR-202611-0001" class="w-full py-3 px-4 rounded-2xl border border-slate-300 text-sm font-mono uppercase tracking-wider focus:border-indigo-600 shadow-xs" />
                    </div>
                    <button type="submit" class="px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-sm shadow-md shadow-indigo-500/20 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Periksa Status</span>
                    </button>
                </form>

                <?php if(session('success')): ?>
                    <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-xl text-xs leading-relaxed">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-900 rounded-xl text-xs leading-relaxed">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Detail Laporan jika ditemukan -->
            <?php if($report): ?>
                <?php
                    $storageBase = rtrim(env('REPORT_STORAGE_URL', 'http://127.0.0.1:8003/storage'), '/');
                    $status = $report['status'];
                    $badgeClass = match($status) {
                        'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
                        'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'ditolak'  => 'bg-rose-100 text-rose-800 border-rose-200',
                        default    => 'bg-slate-100 text-slate-800 border-slate-200',
                    };
                    $stepIndex = match($status) {
                        'pending'  => 1,
                        'diproses' => 2,
                        'selesai'  => 3,
                        'ditolak'  => 0,
                        default    => 1,
                    };
                ?>

                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden p-6 sm:p-8 space-y-7">
                    
                    <!-- Header Tiket & Status Badge -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kode Registrasi Laporan</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-2xl font-mono font-black text-indigo-700"><?php echo e($report['kode_laporan']); ?></span>
                                <button type="button" x-on:click="copyCode('<?php echo e($report['kode_laporan']); ?>')" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition" title="Salin Kode Tiket">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                    </svg>
                                </button>
                                <span x-show="copied" x-cloak class="text-[11px] font-bold text-emerald-600">Disalin!</span>
                            </div>
                        </div>

                        <div>
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold border <?php echo e($badgeClass); ?> uppercase tracking-wide">
                                <?php if($status === 'pending'): ?>
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-beacon"></span>
                                    <span>Menunggu Konfirmasi</span>
                                <?php elseif($status === 'diproses'): ?>
                                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-beacon"></span>
                                    <span>Sedang Ditangani Teknisi</span>
                                <?php elseif($status === 'selesai'): ?>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Selesai Diperbaiki</span>
                                <?php else: ?>
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>Laporan Ditolak</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Progress Stepper (Jika bukan ditolak) -->
                    <?php if($status !== 'ditolak'): ?>
                        <div class="py-2">
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs <?php echo e($stepIndex >= 1 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-slate-200 text-slate-500'); ?>">
                                        1
                                    </div>
                                    <div class="text-xs font-bold <?php echo e($stepIndex >= 1 ? 'text-indigo-900' : 'text-slate-400'); ?>">Laporan Masuk</div>
                                </div>
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs <?php echo e($stepIndex >= 2 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-slate-200 text-slate-500'); ?>">
                                        2
                                    </div>
                                    <div class="text-xs font-bold <?php echo e($stepIndex >= 2 ? 'text-indigo-900' : 'text-slate-400'); ?>">Teknisi Ditugaskan</div>
                                </div>
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs <?php echo e($stepIndex >= 3 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/30' : 'bg-slate-200 text-slate-500'); ?>">
                                        3
                                    </div>
                                    <div class="text-xs font-bold <?php echo e($stepIndex >= 3 ? 'text-emerald-700' : 'text-slate-400'); ?>">Selesai Diperbaiki</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Grid Info Rinci -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold flex items-center gap-1.5">
                                <span>Identitas Pelapor</span>
                                <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-semibold border border-emerald-200">Privasi Terlindungi</span>
                            </span>
                            <div class="font-extrabold text-slate-900 mt-0.5"><?php echo e($report['nama_pelapor']); ?></div>
                            <div class="text-xs text-slate-500 font-mono"><?php echo e($report['kontak_pelapor']); ?></div>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold">Waktu Lapor</span>
                            <div class="text-sm font-bold text-slate-800 mt-0.5">
                                <?php echo e(date('d M Y H:i', strtotime($report['created_at']))); ?> WIB
                            </div>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold">Laboratorium</span>
                            <div class="font-bold text-slate-900 mt-0.5"><?php echo e($report['laboratory']['nama_lab'] ?? '-'); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($report['laboratory']['lokasi'] ?? ''); ?></div>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold">Fasilitas / Unit</span>
                            <div class="font-bold text-slate-900 mt-0.5"><?php echo e($report['facility']['nama_fasilitas'] ?? '-'); ?></div>
                            <div class="text-xs text-indigo-600 font-semibold">Nomor PC: <?php echo e($report['nomor_pc'] ?? 'Tidak Ditentukan'); ?></div>
                        </div>
                    </div>

                    <!-- Teknisi Penanggung Jawab -->
                    <div class="p-5 bg-indigo-50/70 border border-indigo-100 rounded-2xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-indigo-500 uppercase font-bold">Teknisi Penanggung Jawab</span>
                            <div class="text-base font-extrabold text-indigo-950 mt-0.5">
                                <?php echo e($report['technician']['nama'] ?? 'Belum Ditugaskan'); ?>

                            </div>
                            <?php if(isset($report['technician']['spesialisasi'])): ?>
                                <div class="text-xs text-indigo-600 mt-0.5"><?php echo e($report['technician']['spesialisasi']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Deskripsi Masalah Pelapor -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</h4>
                        <div class="p-4 bg-slate-50 rounded-2xl text-sm text-slate-800 whitespace-pre-line leading-relaxed border border-slate-100">
                            <?php echo e($report['deskripsi_masalah']); ?>

                        </div>
                    </div>

                    <!-- Foto Bukti Kerusakan jika ada -->
                    <?php if(! empty($report['attachments'])): ?>
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Bukti Kerusakan</h4>
                            <div class="flex flex-wrap gap-3">
                                <?php $__currentLoopData = $report['attachments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($storageBase . '/' . $att['file_path']); ?>" target="_blank" class="inline-block border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:opacity-90 transition">
                                        <img src="<?php echo e($storageBase . '/' . $att['file_path']); ?>" alt="Bukti Kerusakan" class="max-h-60 object-contain bg-black/5" />
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tanggapan & Tindakan Petugas -->
                    <?php if(! empty($report['tanggapan'])): ?>
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan Penanganan Petugas</h4>
                            <div class="p-4 bg-emerald-50/80 border border-emerald-200 rounded-2xl text-sm text-emerald-950 whitespace-pre-line leading-relaxed">
                                <?php echo e($report['tanggapan']); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Timeline Riwayat -->
                    <?php if(! empty($report['histories'])): ?>
                        <div class="pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Riwayat Aktivitas Penanganan</span>
                            </h4>

                            <div class="space-y-3.5">
                                <?php $__currentLoopData = $report['histories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-start gap-3.5">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 mt-1.5 shrink-0 ring-4 ring-indigo-50"></div>
                                        <div class="flex-1 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                                            <div class="flex items-center justify-between text-xs text-slate-500">
                                                <span class="font-bold text-slate-800"><?php echo e($history['changed_by']); ?></span>
                                                <span><?php echo e(date('d M Y H:i', strtotime($history['created_at']))); ?> WIB</span>
                                            </div>
                                            <div class="text-sm text-slate-700 mt-1">
                                                <?php echo e($history['catatan'] ?: 'Status diubah ke ' . $history['status_to']); ?>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Daftar Hasil Penanganan Laporan Terkini (Menu Hasil Laporan Publik) -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Hasil Penanganan Laporan Terkini</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Transparansi Lab</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar pembaruan status perbaikan fasilitas di seluruh laboratorium komputer</p>
                    </div>
                    <div class="text-[11px] text-slate-400 italic">
                        *Identitas pelapor disamarkan demi privasi
                    </div>
                </div>

                <?php if(!empty($recentReports) && count($recentReports) > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $itemStatus = $item['status'];
                                $itemBadge = match($itemStatus) {
                                    'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'ditolak'  => 'bg-rose-100 text-rose-800 border-rose-200',
                                    default    => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                                $katBadge = match(strtolower($item['kategori'])) {
                                    'software' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'hardware' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'jaringan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    default    => 'bg-slate-50 text-slate-700 border-slate-200',
                                };
                            ?>
                            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-white hover:border-indigo-100 hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-mono text-xs font-bold text-indigo-700"><?php echo e($item['kode_laporan']); ?></span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border <?php echo e($katBadge); ?>">
                                            <?php echo e($item['kategori']); ?>

                                        </span>
                                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full border <?php echo e($itemBadge); ?>">
                                            <?php echo e(ucfirst($itemStatus)); ?>

                                        </span>
                                    </div>
                                    <div class="text-sm font-bold text-slate-900">
                                        <?php echo e($item['fasilitas']); ?>

                                        <?php if(!empty($item['nomor_pc'])): ?>
                                            <span class="text-slate-400 font-normal">(<?php echo e($item['nomor_pc']); ?>)</span>
                                        <?php endif; ?>
                                        &bull; <span class="text-xs text-slate-600 font-medium"><?php echo e($item['laboratorium']); ?></span>
                                    </div>
                                    <p class="text-xs text-slate-500 line-clamp-1">
                                        <?php echo e($item['deskripsi_masalah']); ?>

                                    </p>
                                    <div class="flex items-center gap-3 text-[11px] text-slate-400 pt-0.5">
                                        <span>Pelapor: <strong class="text-slate-600"><?php echo e($item['nama_pelapor']); ?></strong></span>
                                        <span>&bull;</span>
                                        <span>Teknisi: <strong class="text-indigo-600"><?php echo e($item['teknisi']); ?></strong></span>
                                        <span>&bull;</span>
                                        <span><?php echo e($item['tanggal_update']); ?></span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <a href="<?php echo e(route('pelapor.track', ['kode' => $item['kode_laporan']])); ?>" class="px-3.5 py-2 text-xs font-bold bg-white hover:bg-indigo-50 text-indigo-600 hover:text-indigo-700 border border-slate-200 hover:border-indigo-200 rounded-xl transition inline-flex items-center gap-1.5 shadow-xs">
                                        <span>Cek Progres</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-xs text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        Belum ada laporan perbaikan yang tercatat.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; <?php echo e(date('Y')); ?> <strong>LabReport</strong> &bull; Universitas Nurul Jadid
    </footer>

</body>
</html>
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views/pelapor/complaints/track.blade.php ENDPATH**/ ?>