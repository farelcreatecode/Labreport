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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Data Fasilitas Laboratorium')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Kategori -->
            <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-semibold text-gray-700">Filter Kategori:</span>
                    <form method="GET" action="<?php echo e(route('facilities.index')); ?>" class="flex items-center space-x-2">
                        <select name="category_id" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kategori</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e($selectedCategoryId == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->nama_kategori); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($selectedCategoryId): ?>
                            <a href="<?php echo e(route('facilities.index')); ?>" class="text-xs text-indigo-600 hover:underline">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Ringkasan Unit -->
                <div class="flex items-center space-x-4 text-sm">
                    <span class="text-gray-600">Total Unit: <strong class="text-gray-900"><?php echo e($facilityStats['total_units']); ?></strong></span>
                    <span class="text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full font-medium">Baik: <?php echo e($facilityStats['total_baik']); ?></span>
                    <span class="text-red-700 bg-red-50 px-2.5 py-1 rounded-full font-medium">Rusak: <?php echo e($facilityStats['total_rusak']); ?></span>
                </div>
            </div>

            <!-- Rekapitulasi Per Kategori Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php $__currentLoopData = $facilityStats['by_category']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                        <div class="text-xs text-gray-500 uppercase font-semibold"><?php echo e($cat->nama_kategori); ?></div>
                        <div class="mt-2 text-2xl font-bold text-gray-800"><?php echo e($cat->total_units); ?> <span class="text-xs font-normal text-gray-500">unit</span></div>
                        <div class="mt-2 flex justify-center space-x-2 text-xs">
                            <span class="text-emerald-600 font-semibold"><?php echo e($cat->baik_units); ?> Baik</span>
                            <span class="text-gray-300">|</span>
                            <span class="text-red-600 font-semibold"><?php echo e($cat->rusak_units); ?> Rusak</span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Rincian Fasilitas Per Laboratorium -->
            <div class="space-y-6">
                <?php $__currentLoopData = $facilityStats['by_lab']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg"><?php echo e($lab->nama_lab); ?></h3>
                                <p class="text-xs text-gray-500"><?php echo e($lab->lokasi); ?> &bull; Kepala Lab: <?php echo e($lab->kepala_lab ?? '-'); ?></p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-white border border-gray-200 rounded text-gray-700">
                                <?php echo e($lab->facilities->count()); ?> Fasilitas
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama Fasilitas / Unit</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Kategori</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Kondisi Saat Ini</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <?php $__empty_1 = true; $__currentLoopData = $lab->facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3 font-medium text-gray-900"><?php echo e($fac->nama_fasilitas); ?></td>
                                            <td class="px-6 py-3 text-gray-600"><?php echo e($fac->category->nama_kategori ?? '-'); ?></td>
                                            <td class="px-6 py-3">
                                                <?php if($fac->kondisi === 'baik'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                        ✓ Baik
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        ✗ Rusak
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-3 text-right">
                                                <?php if($fac->kondisi === 'rusak'): ?>
                                                    <a href="<?php echo e(route('complaints.create')); ?>?lab_id=<?php echo e($lab->id); ?>&category_id=<?php echo e($fac->category_id); ?>" class="text-xs text-indigo-600 hover:underline font-semibold">
                                                        Laporkan Kendala
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-xs text-gray-400">Normal</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">
                                                Tidak ada fasilitas terdaftar untuk laboratorium ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views\pelapor\facilities\index.blade.php ENDPATH**/ ?>