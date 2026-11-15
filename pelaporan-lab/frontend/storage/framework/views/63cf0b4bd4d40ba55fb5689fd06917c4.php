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
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Statistik & Analisis Laporan Kerusakan
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Analisis performa penanganan dan sebaran kerusakan fasilitas</p>
            </div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1">
                <span>&larr;</span>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <?php
        $counts = $stats['counts'] ?? ['total' => 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0, 'ditolak' => 0];
        $categories = $stats['categories'] ?? ['hardware' => 0, 'software' => 0, 'jaringan' => 0];
    ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-7">

            <!-- Summary Status 4 Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Laporan Masuk</span>
                    <div class="text-3xl font-black text-slate-900 mt-2"><?php echo e($counts['total']); ?></div>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-amber-200/80 shadow-xs border-l-4 border-l-amber-500">
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Menunggu (Pending)</span>
                    <div class="text-3xl font-black text-amber-600 mt-2"><?php echo e($counts['pending']); ?></div>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-blue-200/80 shadow-xs border-l-4 border-l-blue-500">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Sedang Diproses</span>
                    <div class="text-3xl font-black text-blue-600 mt-2"><?php echo e($counts['diproses']); ?></div>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-emerald-200/80 shadow-xs border-l-4 border-l-emerald-500">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Selesai Diperbaiki</span>
                    <div class="text-3xl font-black text-emerald-600 mt-2"><?php echo e($counts['selesai']); ?></div>
                </div>
            </div>

            <!-- Breakdown Kategori Kerusakan -->
            <div class="bg-white p-7 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80">
                <h3 class="text-base font-extrabold text-slate-900 mb-1">Distribusi Berdasarkan Kategori Kerusakan</h3>
                <p class="text-xs text-slate-400 mb-6">Persentase laporan yang masuk per kelompok bidang fasilitas</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="p-5 bg-purple-50/60 rounded-2xl border border-purple-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Software / OS</span>
                            <div class="text-3xl font-black text-purple-950 mt-1"><?php echo e($categories['software']); ?></div>
                            <p class="text-[11px] text-purple-600 mt-0.5">Aplikasi & Lisensi</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-5 bg-blue-50/60 rounded-2xl border border-blue-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Hardware / PC</span>
                            <div class="text-3xl font-black text-blue-950 mt-1"><?php echo e($categories['hardware']); ?></div>
                            <p class="text-[11px] text-blue-600 mt-0.5">Perangkat Keras & Monitor</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-5 bg-teal-50/60 rounded-2xl border border-teal-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Jaringan LAN / Switch</span>
                            <div class="text-3xl font-black text-teal-950 mt-1"><?php echo e($categories['jaringan']); ?></div>
                            <p class="text-[11px] text-teal-600 mt-0.5">Konektivitas & Router</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                            </svg>
                        </div>
                    </div>
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
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views\admin\statistics\index.blade.php ENDPATH**/ ?>