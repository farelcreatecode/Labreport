<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Dashboard Administrator
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Monitoring status pengaduan dan fasilitas lab komputer</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="<?php echo e(route('admin.complaints.index')); ?>" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Kelola Semua Laporan</span>
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <?php
        $counts = $dashboardData['counts'] ?? ['total' => 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0, 'ditolak' => 0];
        $categories = $dashboardData['categories'] ?? ['hardware' => 0, 'software' => 0, 'jaringan' => 0];
        $categoryDetails = $dashboardData['category_details'] ?? [
            'software' => ['total' => $categories['software'] ?? 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0],
            'hardware' => ['total' => $categories['hardware'] ?? 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0],
            'jaringan' => ['total' => $categories['jaringan'] ?? 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0],
        ];
        $latestReports = $dashboardData['latest_reports'] ?? [];
    ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-7">

            <!-- 3 Card Utama Terpadu: Status Menunggu, Sedang Diproses, dan Selesai dijadikan satu ke dalam Software, Hardware, & Jaringan -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                            <span>Status Laporan Per Kategori Fasilitas</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ringkasan status menunggu, sedang diproses, dan selesai untuk setiap kategori</p>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-600 rounded-full border border-slate-200">
                        Total Tiket: <strong><?php echo e($counts['total']); ?></strong>
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- CARD 1: TENTANG SOFTWARE -->
                    <div class="bg-white rounded-3xl border border-purple-200/80 shadow-xs hover:shadow-lg transition-all overflow-hidden border-t-4 border-t-purple-600 flex flex-col justify-between">
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-black uppercase tracking-wider text-purple-700">1. Tentang Software</span>
                                    <div class="text-3xl font-black text-slate-900 mt-1">
                                        <?php echo e($categoryDetails['software']['total'] ?? 0); ?> <span class="text-xs font-bold text-slate-400 normal-case">Laporan</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">Sistem Operasi, Aplikasi Lab, Lisensi & Konfigurasi</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-xs shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Status Menunggu, Sedang Diproses, dan Selesai Terintegrasi -->
                        <div class="p-6 pt-0 space-y-2 border-t border-slate-100 bg-slate-50/50">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 pt-3 pb-1">Progres Penanganan:</div>

                            <!-- Menunggu -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'software', 'status' => 'pending'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/90 hover:bg-amber-100/90 border border-amber-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-beacon"></span>
                                    <span class="text-xs font-bold text-amber-900 group-hover:text-amber-950">Menunggu (Pending)</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-amber-200/80 text-amber-900 font-mono">
                                    <?php echo e($categoryDetails['software']['pending'] ?? 0); ?>

                                </span>
                            </a>

                            <!-- Sedang Diproses -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'software', 'status' => 'diproses'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-blue-50/90 hover:bg-blue-100/90 border border-blue-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="text-xs font-bold text-blue-900 group-hover:text-blue-950">Sedang Diproses</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-blue-200/80 text-blue-900 font-mono">
                                    <?php echo e($categoryDetails['software']['diproses'] ?? 0); ?>

                                </span>
                            </a>

                            <!-- Selesai -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'software', 'status' => 'selesai'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50/90 hover:bg-emerald-100/90 border border-emerald-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-bold text-emerald-900 group-hover:text-emerald-950">Selesai Diperbaiki</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-emerald-200/80 text-emerald-900 font-mono">
                                    <?php echo e($categoryDetails['software']['selesai'] ?? 0); ?>

                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- CARD 2: TENTANG HARDWARE -->
                    <div class="bg-white rounded-3xl border border-blue-200/80 shadow-xs hover:shadow-lg transition-all overflow-hidden border-t-4 border-t-blue-600 flex flex-col justify-between">
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-black uppercase tracking-wider text-blue-700">2. Tentang Hardware</span>
                                    <div class="text-3xl font-black text-slate-900 mt-1">
                                        <?php echo e($categoryDetails['hardware']['total'] ?? 0); ?> <span class="text-xs font-bold text-slate-400 normal-case">Laporan</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">PC, Monitor, Keyboard, Mouse, RAM & Printer</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-xs shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Status Menunggu, Sedang Diproses, dan Selesai Terintegrasi -->
                        <div class="p-6 pt-0 space-y-2 border-t border-slate-100 bg-slate-50/50">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 pt-3 pb-1">Progres Penanganan:</div>

                            <!-- Menunggu -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'hardware', 'status' => 'pending'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/90 hover:bg-amber-100/90 border border-amber-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-beacon"></span>
                                    <span class="text-xs font-bold text-amber-900 group-hover:text-amber-950">Menunggu (Pending)</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-amber-200/80 text-amber-900 font-mono">
                                    <?php echo e($categoryDetails['hardware']['pending'] ?? 0); ?>

                                </span>
                            </a>

                            <!-- Sedang Diproses -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'hardware', 'status' => 'diproses'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-blue-50/90 hover:bg-blue-100/90 border border-blue-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="text-xs font-bold text-blue-900 group-hover:text-blue-950">Sedang Diproses</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-blue-200/80 text-blue-900 font-mono">
                                    <?php echo e($categoryDetails['hardware']['diproses'] ?? 0); ?>

                                </span>
                            </a>

                            <!-- Selesai -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'hardware', 'status' => 'selesai'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50/90 hover:bg-emerald-100/90 border border-emerald-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-bold text-emerald-900 group-hover:text-emerald-950">Selesai Diperbaiki</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-emerald-200/80 text-emerald-900 font-mono">
                                    <?php echo e($categoryDetails['hardware']['selesai'] ?? 0); ?>

                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- CARD 3: TENTANG JARINGAN -->
                    <div class="bg-white rounded-3xl border border-emerald-200/80 shadow-xs hover:shadow-lg transition-all overflow-hidden border-t-4 border-t-emerald-600 flex flex-col justify-between">
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-black uppercase tracking-wider text-emerald-700">3. Tentang Jaringan</span>
                                    <div class="text-3xl font-black text-slate-900 mt-1">
                                        <?php echo e($categoryDetails['jaringan']['total'] ?? 0); ?> <span class="text-xs font-bold text-slate-400 normal-case">Laporan</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">Kabel LAN, WiFi Lab, Switch Hub & Router</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-xs shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Status Menunggu, Sedang Diproses, dan Selesai Terintegrasi -->
                        <div class="p-6 pt-0 space-y-2 border-t border-slate-100 bg-slate-50/50">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 pt-3 pb-1">Progres Penanganan:</div>

                            <!-- Menunggu -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'jaringan', 'status' => 'pending'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/90 hover:bg-amber-100/90 border border-amber-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-beacon"></span>
                                    <span class="text-xs font-bold text-amber-900 group-hover:text-amber-950">Menunggu (Pending)</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-amber-200/80 text-amber-900 font-mono">
                                    <?php echo e($categoryDetails['jaringan']['pending'] ?? 0); ?>

                                </span>
                            </a>

                            <!-- Sedang Diproses -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'jaringan', 'status' => 'diproses'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-blue-50/90 hover:bg-blue-100/90 border border-blue-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="text-xs font-bold text-blue-900 group-hover:text-blue-950">Sedang Diproses</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-blue-200/80 text-blue-900 font-mono">
                                    <?php echo e($categoryDetails['jaringan']['diproses'] ?? 0); ?>

                                </span>
                            </a>

                            <!-- Selesai -->
                            <a href="<?php echo e(route('admin.complaints.index', ['kategori' => 'jaringan', 'status' => 'selesai'])); ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50/90 hover:bg-emerald-100/90 border border-emerald-200/60 transition group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-bold text-emerald-900 group-hover:text-emerald-950">Selesai Diperbaiki</span>
                                </div>
                                <span class="text-xs font-black px-2 py-0.5 rounded-lg bg-emerald-200/80 text-emerald-900 font-mono">
                                    <?php echo e($categoryDetails['jaringan']['selesai'] ?? 0); ?>

                                </span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Tabel 5 Laporan Terbaru -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Laporan Kerusakan Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">5 tiket terakhir yang dikirim melalui portal publik</p>
                    </div>
                    <a href="<?php echo e(route('admin.complaints.index')); ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                        <span>Lihat Semua Laporan</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Kode Tiket</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4">Laboratorium</th>
                                <th class="px-6 py-4">Fasilitas / Unit</th>
                                <th class="px-6 py-4">Teknisi</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $latestReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-indigo-600 text-sm"><?php echo e($rep['kode_laporan']); ?></span>
                                        <div class="text-[11px] text-slate-400 mt-0.5"><?php echo e(date('d M Y H:i', strtotime($rep['created_at']))); ?> WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900"><?php echo e($rep['nama_pelapor']); ?></div>
                                        <div class="text-xs text-slate-400"><?php echo e($rep['kontak_pelapor']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        <?php echo e($rep['laboratory']['nama_lab'] ?? '-'); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-slate-900"><?php echo e($rep['facility']['nama_fasilitas'] ?? '-'); ?></span>
                                        <?php if(! empty($rep['nomor_pc'])): ?>
                                            <div class="text-xs text-indigo-600 font-semibold mt-0.5">Unit: <?php echo e($rep['nomor_pc']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if(! empty($rep['technician'])): ?>
                                            <span class="font-bold text-slate-900"><?php echo e($rep['technician']['nama']); ?></span>
                                        <?php else: ?>
                                            <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">Belum Ditugaskan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                            $st = $rep['status'];
                                            $badge = match($st) {
                                                'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'ditolak'  => 'bg-rose-100 text-rose-800 border-rose-200',
                                                default    => 'bg-slate-100 text-slate-800 border-slate-200',
                                            };
                                        ?>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($badge); ?> uppercase border">
                                            <?php echo e($st); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="<?php echo e(route('admin.complaints.show', $rep['id'])); ?>" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-xl text-xs font-bold transition">
                                            Kelola &rarr;
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">
                                        Belum ada laporan kerusakan yang tercatat.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>