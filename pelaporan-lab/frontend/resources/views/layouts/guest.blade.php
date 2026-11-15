<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LabReport') }} &mdash; Login Admin</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS & Alpine.js with graceful fallback -->
        @if(file_exists(public_path('build/manifest.json')) || (app()->environment('local') && file_exists(public_path('hot'))))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @endif

        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(12px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-center items-center p-4 bg-gradient-to-br from-slate-100 via-indigo-50/40 to-slate-100">
        
        <!-- Background Grid Pattern Accent -->
        <div class="fixed inset-0 pointer-events-none opacity-40 [background-image:radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px]"></div>

        <div class="w-full sm:max-w-md relative z-10 animate-fade-in">
            <!-- Brand Header -->
            <div class="text-center mb-6">
                <a href="{{ route('pelapor.create') }}" class="inline-flex items-center gap-2.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-slate-900 tracking-tight">LabReport</span>
                </a>
            </div>

            <div class="w-full bg-white/90 backdrop-blur-xl px-8 py-8 shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-200/80">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} LabReport &bull; Universitas Nurul Jadid
            </div>
        </div>
    </body>
</html>
