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
                    Kelola Semua Laporan Kerusakan
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Filter, telusuri, dan tugaskan teknisi untuk penanganan laporan</p>
            </div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <?php if(session('success')): ?>
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-2xl shadow-sm text-sm font-semibold">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-900 rounded-2xl shadow-sm text-sm font-semibold">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <!-- Filter Card Modern -->
            <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80">
                <form method="GET" action="<?php echo e(route('admin.complaints.index')); ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cari Laporan</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Kode Tiket, Nama, No. PC, Gejala..." class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 transition" />
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status</label>
                        <select name="status" class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 bg-white transition">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Menunggu (Pending)</option>
                            <option value="diproses" <?php echo e(request('status') === 'diproses' ? 'selected' : ''); ?>>Diproses</option>
                            <option value="selesai" <?php echo e(request('status') === 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                            <option value="ditolak" <?php echo e(request('status') === 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                        </select>
                    </div>

                    <!-- Filter Lab -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Laboratorium</label>
                        <select name="laboratory_id" class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 bg-white transition">
                            <option value="">Semua Ruang Lab</option>
                            <?php $__currentLoopData = $laboratories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($l['id']); ?>" <?php echo e(request('laboratory_id') == $l['id'] ? 'selected' : ''); ?>><?php echo e($l['nama_lab']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Filter Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori</label>
                        <select name="kategori" class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 bg-white transition">
                            <option value="">Semua Kategori</option>
                            <option value="hardware" <?php echo e(request('kategori') === 'hardware' ? 'selected' : ''); ?>>Hardware</option>
                            <option value="software" <?php echo e(request('kategori') === 'software' ? 'selected' : ''); ?>>Software</option>
                            <option value="jaringan" <?php echo e(request('kategori') === 'jaringan' ? 'selected' : ''); ?>>Jaringan</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-500/20 transition">
                            Filter
                        </button>
                        <a href="<?php echo e(route('admin.complaints.index')); ?>" class="py-2.5 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Laporan -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Kode Tiket</th>
                                <th class="px-6 py-4">Pelapor & Kontak</th>
                                <th class="px-6 py-4">Laboratorium</th>
                                <th class="px-6 py-4">Fasilitas / Unit</th>
                                <th class="px-6 py-4">Teknisi Bertugas</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                        <div class="font-bold text-slate-900"><?php echo e($rep['facility']['nama_fasilitas'] ?? '-'); ?></div>
                                        <?php if(! empty($rep['nomor_pc'])): ?>
                                            <span class="text-xs text-indigo-600 font-semibold">Unit: <?php echo e($rep['nomor_pc']); ?></span>
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
                                        <a href="<?php echo e(route('admin.complaints.show', $rep['id'])); ?>" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-xl text-xs font-bold transition shadow-xs">
                                            Kelola &rarr;
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">
                                        Tidak ada laporan kerusakan yang sesuai dengan kriteria filter.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-400">
                    <div>
                        Menampilkan <strong><?php echo e(count($reports)); ?></strong> dari <strong><?php echo e($pagination['total'] ?? 0); ?></strong> total tiket.
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
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views/admin/complaints/index.blade.php ENDPATH**/ ?>