<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="font-black text-xl text-slate-900 flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>LabReport</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-widest">Admin</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-1">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-3.5 py-2 rounded-xl text-sm font-bold transition-all <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'); ?>">
                        Dashboard
                    </a>

                    <a href="<?php echo e(route('admin.complaints.index')); ?>" class="px-3.5 py-2 rounded-xl text-sm font-bold transition-all <?php echo e(request()->routeIs('admin.complaints.*') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'); ?>">
                        Kelola Laporan
                    </a>

                    <!-- Dropdown Master Data -->
                    <div class="relative" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-bold transition-all <?php echo e(request()->routeIs('admin.facilities.*') || request()->routeIs('admin.labs.*') || request()->routeIs('admin.technicians.*') || request()->routeIs('admin.statistics.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'); ?>">
                            <span>Data Master</span>
                            <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': dropdownOpen}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 rounded-2xl bg-white shadow-xl shadow-slate-200/50 border border-slate-100 py-2 z-50">
                            <div class="px-4 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Master Laboratorium</div>
                            <a href="<?php echo e(route('admin.labs.index')); ?>" class="flex items-center px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                Ruang Laboratorium
                            </a>
                            <a href="<?php echo e(route('admin.facilities.index')); ?>" class="flex items-center px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                Fasilitas & Unit PC
                            </a>
                            <a href="<?php echo e(route('admin.technicians.index')); ?>" class="flex items-center px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                Teknisi Lapangan
                            </a>
                        </div>
                    </div>

                    <!-- Direct link ke Public Portal -->
                    <a href="<?php echo e(route('pelapor.create')); ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold text-slate-400 hover:text-indigo-600 transition">
                        <span>Portal Publik</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Settings / User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    <span><?php echo e(session('admin_user.name', 'Administrator')); ?></span>
                </span>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

            <!-- Hamburger Button for Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-200/80 bg-white px-4 pt-3 pb-5 space-y-2">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-3 py-2 rounded-xl text-sm font-bold <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700'); ?>">
            Dashboard
        </a>
        <a href="<?php echo e(route('admin.complaints.index')); ?>" class="block px-3 py-2 rounded-xl text-sm font-bold <?php echo e(request()->routeIs('admin.complaints.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700'); ?>">
            Kelola Laporan
        </a>
        <div class="pt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3">Data Master</div>
        <a href="<?php echo e(route('admin.labs.index')); ?>" class="block px-3 py-1.5 text-xs font-semibold text-slate-700">
            Data Laboratorium
        </a>
        <a href="<?php echo e(route('admin.facilities.index')); ?>" class="block px-3 py-1.5 text-xs font-semibold text-slate-700">
            Data Fasilitas & Unit
        </a>
        <a href="<?php echo e(route('admin.technicians.index')); ?>" class="block px-3 py-1.5 text-xs font-semibold text-slate-700">
            Data Teknisi Lapangan
        </a>
        <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-800"><?php echo e(session('admin_user.name', 'Administrator')); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-xs font-bold text-rose-600">Logout</button>
            </form>
        </div>
    </div>
</nav>
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views\layouts\navigation.blade.php ENDPATH**/ ?>