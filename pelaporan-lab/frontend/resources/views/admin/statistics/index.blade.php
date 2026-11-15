<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Statistik & Analisis Laporan Kerusakan') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    @php
        $counts = $stats['counts'] ?? ['total' => 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0, 'ditolak' => 0];
        $categories = $stats['categories'] ?? ['hardware' => 0, 'software' => 0, 'jaringan' => 0];
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Status 4 Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase">Total Laporan</span>
                    <div class="text-3xl font-extrabold text-gray-900 mt-1">{{ $counts['total'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-amber-500">
                    <span class="text-xs font-bold text-amber-600 uppercase">Menunggu (Pending)</span>
                    <div class="text-3xl font-extrabold text-amber-600 mt-1">{{ $counts['pending'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
                    <span class="text-xs font-bold text-blue-600 uppercase">Diproses</span>
                    <div class="text-3xl font-extrabold text-blue-600 mt-1">{{ $counts['diproses'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-emerald-500">
                    <span class="text-xs font-bold text-emerald-600 uppercase">Selesai</span>
                    <div class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $counts['selesai'] }}</div>
                </div>
            </div>

            <!-- Breakdown Kategori -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4">Distribusi Berdasarkan Kategori Kerusakan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <span class="text-xs font-bold text-purple-700 uppercase">Software / Aplikasi</span>
                        <div class="text-2xl font-black text-purple-900 mt-1">{{ $categories['software'] }} Laporan</div>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <span class="text-xs font-bold text-blue-700 uppercase">Hardware / Unit PC</span>
                        <div class="text-2xl font-black text-blue-900 mt-1">{{ $categories['hardware'] }} Laporan</div>
                    </div>
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <span class="text-xs font-bold text-emerald-700 uppercase">Jaringan / Konektivitas</span>
                        <div class="text-2xl font-black text-emerald-900 mt-1">{{ $categories['jaringan'] }} Laporan</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
