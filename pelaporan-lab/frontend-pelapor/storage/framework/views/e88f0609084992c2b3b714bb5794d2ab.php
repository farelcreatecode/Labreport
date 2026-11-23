<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'LabReport')); ?> &mdash; Panel Administrator</title>

        <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS & Alpine.js with graceful fallback -->
        <?php if(file_exists(public_path('build/manifest.json')) || (app()->environment('local') && file_exists(public_path('hot')))): ?>
            <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <?php else: ?>
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                                mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                            },
                            colors: {
                                brand: {
                                    50: '#eef2ff',
                                    100: '#e0e7ff',
                                    200: '#c7d2fe',
                                    500: '#6366f1',
                                    600: '#4f46e5',
                                    700: '#4338ca',
                                    800: '#3730a3',
                                    900: '#312e81',
                                    950: '#1e1b4b',
                                },
                            }
                        }
                    }
                }
            </script>
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <?php endif; ?>

        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
            .font-mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeInUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes pulseGlow {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.4; transform: scale(1.2); }
            }
            .animate-beacon {
                animation: pulseGlow 2s infinite ease-in-out;
            }

            /* Custom slim scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f5f9;
            }
            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 9999px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col justify-between">
        <div>
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-16 z-20 transition-all">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main class="animate-fade-in">
                <?php echo e($slot); ?>

            </main>
        </div>

        <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    &copy; <?php echo e(date('Y')); ?> <strong>LabReport</strong> &bull; Sistem Informasi Pelaporan Fasilitas Laboratorium Komputer
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-beacon"></span>
                        Microservices Active
                    </span>
                    <a href="<?php echo e(route('pelapor.create')); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        Portal Publik &nearr;
                    </a>
                </div>
            </div>
        </footer>
    </body>
</html>
<?php /**PATH C:\laragon\www\Backend Micro\pelaporan-lab\frontend\resources\views/layouts/app.blade.php ENDPATH**/ ?>