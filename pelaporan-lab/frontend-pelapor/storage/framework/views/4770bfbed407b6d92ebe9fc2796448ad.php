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
                    Kelola Tiket: <span class="font-mono text-indigo-600"><?php echo e($report['kode_laporan']); ?></span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Penugasan teknisi dan pembaruan status laporan</p>
            </div>
            <a href="<?php echo e(route('admin.complaints.index')); ?>" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1">
                <span>&larr;</span>
                <span>Kembali ke Kelola Laporan</span>
            </a>
        </div>
     <?php $__env->endSlot(); ?>

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
    ?>

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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Detail Laporan & Timeline Riwayat (2 Kolom) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Kartu Info Laporan -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Terkini</span>
                                <div class="mt-1">
                                    <span class="px-3.5 py-1 text-xs font-bold rounded-full uppercase border <?php echo e($badgeClass); ?>">
                                        <?php echo e($status === 'pending' ? 'Menunggu Konfirmasi' : ucfirst($status)); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu Masuk</span>
                                <div class="text-sm font-bold text-slate-800 mt-1">
                                    <?php echo e(date('d M Y H:i', strtotime($report['created_at']))); ?> WIB
                                </div>
                            </div>
                        </div>

                        <!-- Data Pelapor Publik (Tanpa Login) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pelapor</span>
                                <div class="font-extrabold text-slate-900 mt-0.5 text-base"><?php echo e($report['nama_pelapor']); ?></div>
                                <span class="inline-block mt-0.5 text-[11px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                                    Pelapor Publik (Tanpa Akun)
                                </span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kontak Pelapor</span>
                                <div class="text-sm font-bold text-slate-800 mt-0.5"><?php echo e($report['kontak_pelapor']); ?></div>
                            </div>
                        </div>

                        <!-- Lokasi & Fasilitas -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laboratorium</span>
                                <div class="text-base font-extrabold text-slate-900 mt-0.5"><?php echo e($report['laboratory']['nama_lab'] ?? '-'); ?></div>
                                <div class="text-xs text-slate-500"><?php echo e($report['laboratory']['lokasi'] ?? ''); ?></div>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Fasilitas / Unit PC</span>
                                <div class="text-base font-extrabold text-slate-900 mt-0.5"><?php echo e($report['facility']['nama_fasilitas'] ?? '-'); ?></div>
                                <div class="text-xs text-indigo-600 font-semibold mt-0.5">Nomor PC: <?php echo e($report['nomor_pc'] ?? 'Tidak ditentukan'); ?></div>
                            </div>
                        </div>

                        <!-- Deskripsi Kerusakan -->
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Deskripsi Kerusakan Pelapor</span>
                            <div class="p-4 bg-slate-50 rounded-2xl text-sm text-slate-800 leading-relaxed whitespace-pre-line border border-slate-100">
                                <?php echo e($report['deskripsi_masalah']); ?>

                            </div>
                        </div>

                        <!-- Foto Bukti -->
                        <?php if(! empty($report['attachments'])): ?>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Foto Bukti Fisik Kerusakan</span>
                                <div class="flex flex-wrap gap-3">
                                    <?php $__currentLoopData = $report['attachments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e($storageBase . '/' . $att['file_path']); ?>" target="_blank" class="inline-block border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:opacity-90 transition">
                                            <img src="<?php echo e($storageBase . '/' . $att['file_path']); ?>" alt="Foto Bukti" class="max-h-72 object-contain bg-black/5" />
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Kartu Timeline Riwayat Laporan (Audit Trail) -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-6 sm:p-8 space-y-4">
                        <h3 class="text-base font-extrabold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span>Riwayat Perubahan Status & Penugasan</span>
                        </h3>

                        <div class="space-y-4 pt-2">
                            <?php $__empty_1 = true; $__currentLoopData = $report['histories'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600 mt-2 shrink-0 ring-4 ring-indigo-50"></div>
                                    <div class="flex-1 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-extrabold text-slate-900"><?php echo e($hist['changed_by']); ?></span>
                                            <span class="text-slate-400"><?php echo e(date('d M Y H:i', strtotime($hist['created_at']))); ?> WIB</span>
                                        </div>
                                        <div class="text-sm text-slate-700 mt-1.5 leading-relaxed">
                                            <?php echo e($hist['catatan'] ?: 'Status diubah menjadi ' . $hist['status_to']); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-xs text-slate-400 italic">Belum ada riwayat penanganan tercatat.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Form Tindak Lanjut & Penugasan Teknisi (1 Kolom Sticky) -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-6 sm:p-7 space-y-5 h-fit sticky top-28">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">
                            Tindak Lanjut & Penugasan
                        </h3>
                    </div>

                    <form method="POST" action="<?php echo e(route('admin.complaints.update', $report['id'])); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Perbarui Status Tiket</label>
                            <select id="status" name="status" required class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm font-semibold focus:border-indigo-600 bg-white transition">
                                <option value="pending" <?php echo e($report['status'] === 'pending' ? 'selected' : ''); ?>>Menunggu (Pending)</option>
                                <option value="diproses" <?php echo e($report['status'] === 'diproses' ? 'selected' : ''); ?>>Diproses (Sedang Diperbaiki)</option>
                                <option value="selesai" <?php echo e($report['status'] === 'selesai' ? 'selected' : ''); ?>>Selesai (Tuntas Diperbaiki)</option>
                                <option value="ditolak" <?php echo e($report['status'] === 'ditolak' ? 'selected' : ''); ?>>Ditolak (Tidak Sesuai)</option>
                            </select>
                        </div>

                        <!-- Tugaskan Teknisi -->
                        <div>
                            <label for="technician_id" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tugaskan Teknisi Lapangan</label>
                            <select id="technician_id" name="technician_id" class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 bg-white transition">
                                <option value="">-- Pilih Teknisi (Opsional) --</option>
                                <?php $__currentLoopData = $technicians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tech['id']); ?>" <?php echo e(($report['technician_id'] ?? null) == $tech['id'] ? 'selected' : ''); ?>>
                                        <?php echo e($tech['nama']); ?> (<?php echo e($tech['spesialisasi']); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Teknisi dipilih oleh admin melalui data master teknisi.</p>
                        </div>

                        <!-- Catatan / Tanggapan -->
                        <div>
                            <label for="tanggapan" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Catatan Tindakan / Hasil Perbaikan
                            </label>
                            <textarea id="tanggapan" name="tanggapan" rows="5" placeholder="Tuliskan tindakan teknisi, komponen yang diganti, atau alasan penolakan..." class="w-full py-2.5 px-3.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-600 leading-relaxed transition"><?php echo e(old('tanggapan', $report['tanggapan'])); ?></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-indigo-500/20 transition-all hover:scale-[1.01]">
                            Simpan Perubahan Tiket
                        </button>
                    </form>
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
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views\admin\complaints\show.blade.php ENDPATH**/ ?>