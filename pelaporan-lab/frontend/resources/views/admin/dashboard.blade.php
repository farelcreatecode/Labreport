<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }} &mdash; <span class="text-indigo-600">Administrator Lab</span>
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Sistem Pengaduan Kerusakan Fasilitas Laboratorium Komputer</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.complaints.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 shadow-sm transition">
                    Kelola Semua Laporan
                </a>
                <a href="{{ route('admin.statistics.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                    Statistik Lengkap
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $counts = $dashboardData['counts'] ?? ['total' => 0, 'pending' => 0, 'diproses' => 0, 'selesai' => 0, 'ditolak' => 0];
        $categories = $dashboardData['categories'] ?? ['hardware' => 0, 'software' => 0, 'jaringan' => 0];
        $latestReports = $dashboardData['latest_reports'] ?? [];
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
                    <span class="text-xs font-bold text-blue-600 uppercase">Diproses / Penugasan</span>
                    <div class="text-3xl font-extrabold text-blue-600 mt-1">{{ $counts['diproses'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-emerald-500">
                    <span class="text-xs font-bold text-emerald-600 uppercase">Selesai Diperbaiki</span>
                    <div class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $counts['selesai'] }}</div>
                </div>
            </div>

            <!-- Category Summary Cards: Software, Hardware, Jaringan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Total Laporan Software -->
                <a href="{{ route('admin.complaints.index', ['kategori' => 'software']) }}" class="group bg-white p-5 rounded-xl shadow-sm border-l-4 border-purple-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Laporan Software</div>
                            <div class="mt-2 text-3xl font-extrabold text-purple-600 group-hover:text-purple-700">
                                {{ $categories['software'] }}
                            </div>
                            <div class="mt-1 text-xs text-gray-400">Aplikasi, OS & Konfigurasi</div>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-100 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Total Laporan Hardware -->
                <a href="{{ route('admin.complaints.index', ['kategori' => 'hardware']) }}" class="group bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Laporan Hardware</div>
                            <div class="mt-2 text-3xl font-extrabold text-blue-600 group-hover:text-blue-700">
                                {{ $categories['hardware'] }}
                            </div>
                            <div class="mt-1 text-xs text-gray-400">PC, Monitor & Perangkat Fisik</div>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-100 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Total Laporan Jaringan -->
                <a href="{{ route('admin.complaints.index', ['kategori' => 'jaringan']) }}" class="group bg-white p-5 rounded-xl shadow-sm border-l-4 border-emerald-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Laporan Jaringan</div>
                            <div class="mt-2 text-3xl font-extrabold text-emerald-600 group-hover:text-emerald-700">
                                {{ $categories['jaringan'] }}
                            </div>
                            <div class="mt-1 text-xs text-gray-400">Kabel LAN, Switch & Mikrotik</div>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-100 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Content Grid: Latest Reports -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Laporan Kerusakan Terbaru</h3>
                        <p class="text-xs text-gray-500">5 laporan terakhir yang dikirim oleh pelapor</p>
                    </div>
                    <a href="{{ route('admin.complaints.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Lihat Semua Laporan &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-400 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">Kode Tiket</th>
                                <th class="px-6 py-3">Pelapor</th>
                                <th class="px-6 py-3">Laboratorium</th>
                                <th class="px-6 py-3">Fasilitas</th>
                                <th class="px-6 py-3">Teknisi</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($latestReports as $rep)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                        {{ $rep['kode_laporan'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $rep['nama_pelapor'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $rep['kontak_pelapor'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $rep['laboratory']['nama_lab'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-gray-800">{{ $rep['facility']['nama_fasilitas'] ?? '-' }}</span>
                                        @if(! empty($rep['nomor_pc']))
                                            <div class="text-xs text-indigo-600">Unit: {{ $rep['nomor_pc'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $rep['technician']['nama'] ?? 'Belum Ditugaskan' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $st = $rep['status'];
                                            $badge = match($st) {
                                                'pending'  => 'bg-amber-100 text-amber-800',
                                                'diproses' => 'bg-blue-100 text-blue-800',
                                                'selesai'  => 'bg-emerald-100 text-emerald-800',
                                                'ditolak'  => 'bg-red-100 text-red-800',
                                                default    => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }} uppercase">
                                            {{ $st }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.complaints.show', $rep['id']) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                            Kelola &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400 italic">
                                        Belum ada laporan kerusakan yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
