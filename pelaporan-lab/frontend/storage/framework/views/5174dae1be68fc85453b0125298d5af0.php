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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Riwayat Laporan Kerusakan')); ?>

            </h2>
            <a href="<?php echo e(route('complaints.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Buat Laporan Baru
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <?php if(session('success')): ?>
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- Search and Filter Bar -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <form method="GET" action="<?php echo e(route('complaints.index')); ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search input -->
                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-500 uppercase">Cari Laporan</label>
                        <input type="text" id="search" name="search" value="<?php echo e(request('search')); ?>" placeholder="No. laporan, fasilitas, nomor PC..." class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-500 uppercase">Status Laporan</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                            <option value="diproses" <?php echo e(request('status') === 'diproses' ? 'selected' : ''); ?>>Diproses</option>
                            <option value="selesai" <?php echo e(request('status') === 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                            <option value="ditolak" <?php echo e(request('status') === 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                        </select>
                    </div>

                    <!-- Submit / Reset buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow-sm">
                            Filter
                        </button>
                        <a href="<?php echo e(route('complaints.index')); ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Complaint Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">No. Laporan</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Laboratorium</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Kategori</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">No. PC</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Deskripsi Masalah</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                <th class="px-6 py-3 text-right font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                        <?php echo e($complaint->report_number); ?>

                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($complaint->lab->nama_lab ?? '-'); ?></td>
                                    <td class="px-6 py-4 text-gray-700"><?php echo e($complaint->category->nama_kategori ?? '-'); ?></td>
                                    <td class="px-6 py-4 text-gray-700"><?php echo e($complaint->nomor_pc ?? '-'); ?></td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="<?php echo e($complaint->deskripsi_masalah); ?>">
                                        <?php echo e(\Illuminate\Support\Str::limit($complaint->deskripsi_masalah, 45)); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($complaint->status === 'pending'): ?>
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Menunggu</span>
                                        <?php elseif($complaint->status === 'diproses'): ?>
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                                        <?php elseif($complaint->status === 'selesai'): ?>
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Selesai</span>
                                        <?php else: ?>
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap"><?php echo e($complaint->created_at->format('d/m/Y H:i')); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="<?php echo e(route('complaints.show', $complaint)); ?>" class="inline-flex items-center px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded text-xs font-semibold">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ditemukan laporan yang sesuai dengan pencarian atau filter.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($complaints->hasPages()): ?>
                    <div class="p-4 border-t border-gray-100">
                        <?php echo e($complaints->links()); ?>

                    </div>
                <?php endif; ?>
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
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views\pelapor\complaints\index.blade.php ENDPATH**/ ?>