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
                <?php echo e(__('Kelola Pengguna Sistem')); ?>

            </h2>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-wider hover:bg-indigo-700">
                + Tambah Pengguna
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
            <?php if(session('error')): ?>
                <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <!-- Filter Card -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Cari Nama / Email / NIM</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, email, nim/nip..." class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Role Pengguna</label>
                        <select name="role" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Role</option>
                            <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="teknisi" <?php echo e(request('role') === 'teknisi' ? 'selected' : ''); ?>>Teknisi</option>
                            <option value="mahasiswa" <?php echo e(request('role') === 'mahasiswa' ? 'selected' : ''); ?>>Mahasiswa</option>
                            <option value="dosen" <?php echo e(request('role') === 'dosen' ? 'selected' : ''); ?>>Dosen</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow-sm">
                            Filter
                        </button>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">NIM / NIP</th>
                                <th class="px-6 py-3 text-left">Role</th>
                                <th class="px-6 py-3 text-center">Laporan Dibuat</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        <?php echo e($u->name); ?>

                                        <?php if($u->id === Auth::id()): ?>
                                            <span class="text-xs text-indigo-600 font-normal ml-1">(Anda)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700"><?php echo e($u->email); ?></td>
                                    <td class="px-6 py-4 text-gray-700 font-mono"><?php echo e($u->nim_nip ?? '-'); ?></td>
                                    <td class="px-6 py-4">
                                        <?php if($u->role === 'admin'): ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 uppercase">Admin</span>
                                        <?php elseif($u->role === 'teknisi'): ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 uppercase">Teknisi</span>
                                        <?php elseif($u->role === 'dosen'): ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 uppercase">Dosen</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800 uppercase">Mahasiswa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-700"><?php echo e($u->complaints_count); ?></td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="<?php echo e(route('admin.users.edit', $u)); ?>" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                        <?php if($u->id !== Auth::id()): ?>
                                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $u)); ?>" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-6 text-center text-gray-400">Tidak ada pengguna ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($users->hasPages()): ?>
                    <div class="p-4 border-t border-gray-100">
                        <?php echo e($users->links()); ?>

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
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views\admin\users\index.blade.php ENDPATH**/ ?>