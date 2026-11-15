<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Dashboard Administrator
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Monitoring status pengaduan dan fasilitas lab komputer</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.complaints.index') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Kelola Semua Laporan</span>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-7">

            <!-- 3 Category Cards: 1. Tentang Software, 2. Tentang Hardware, 3. Tentang Jaringan -->
            <div>
                <div class="flex items-center justify-between mb-3.5">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>Ringkasan Laporan Berdasarkan Kategori</span>
                    </h3>
                    <span class="text-xs text-slate-400">Klik kartu untuk memfilter laporan</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- 1. Tentang Software -->
                    <a href="{{ route('admin.complaints.index', ['kategori' => 'software']) }}" class="group bg-white p-6 rounded-3xl border border-purple-100 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all border-l-4 border-l-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-extrabold text-purple-700 uppercase tracking-wider">1. Tentang Software</span>
                                <div class="text-3xl font-black text-slate-900 mt-2 group-hover:text-purple-600 transition-colors">
                                    {{ $categories['software'] }} <span class="text-xs text-slate-400 font-semibold font-normal">Laporan</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Sistem Operasi, Aplikasi Lab & Lisensi</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center transition-all shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- 2. Tentang Hardware -->
                    <a href="{{ route('admin.complaints.index', ['kategori' => 'hardware']) }}" class="group bg-white p-6 rounded-3xl border border-blue-100 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all border-l-4 border-l-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-extrabold text-blue-700 uppercase tracking-wider">2. Tentang Hardware</span>
                                <div class="text-3xl font-black text-slate-900 mt-2 group-hover:text-blue-600 transition-colors">
                                    {{ $categories['hardware'] }} <span class="text-xs text-slate-400 font-semibold font-normal">Laporan</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">PC, Monitor, Keyboard, Mouse & Printer</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-all shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- 3. Tentang Jaringan -->
                    <a href="{{ route('admin.complaints.index', ['kategori' => 'jaringan']) }}" class="group bg-white p-6 rounded-3xl border border-emerald-100 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all border-l-4 border-l-emerald-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-extrabold text-emerald-700 uppercase tracking-wider">3. Tentang Jaringan</span>
                                <div class="text-3xl font-black text-slate-900 mt-2 group-hover:text-emerald-600 transition-colors">
                                    {{ $categories['jaringan'] }} <span class="text-xs text-slate-400 font-semibold font-normal">Laporan</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Kabel LAN, WiFi Lab, Switch & Router</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-all shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Status Laporan Overview -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Laporan</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 mt-2">{{ $counts['total'] }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">Keseluruhan tiket masuk</p>
                </div>

                <!-- Pending -->
                <div class="bg-white p-5 rounded-3xl border border-amber-200/80 shadow-xs hover:shadow-md border-l-4 border-l-amber-500 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Menunggu (Pending)</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-beacon"></span>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-amber-600 mt-2">{{ $counts['pending'] }}</div>
                    <p class="text-[11px] text-amber-700/80 mt-1">Perlu diverifikasi & penugasan</p>
                </div>

                <!-- Diproses -->
                <div class="bg-white p-5 rounded-3xl border border-blue-200/80 shadow-xs hover:shadow-md border-l-4 border-l-blue-500 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Sedang Diproses</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-blue-600 mt-2">{{ $counts['diproses'] }}</div>
                    <p class="text-[11px] text-blue-700/80 mt-1">Dalam penanganan teknisi</p>
                </div>

                <!-- Selesai -->
                <div class="bg-white p-5 rounded-3xl border border-emerald-200/80 shadow-xs hover:shadow-md border-l-4 border-l-emerald-500 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Selesai Diperbaiki</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-emerald-600 mt-2">{{ $counts['selesai'] }}</div>
                    <p class="text-[11px] text-emerald-700/80 mt-1">Tuntas dan berfungsi normal</p>
                </div>
            </div>

            <!-- Tabel 5 Laporan Terbaru -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Laporan Kerusakan Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">5 tiket terakhir yang dikirim melalui portal publik</p>
                    </div>
                    <a href="{{ route('admin.complaints.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                        <span>Lihat Semua Laporan</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Kode Tiket</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4">Laboratorium</th>
                                <th class="px-6 py-4">Fasilitas / Unit</th>
                                <th class="px-6 py-4">Teknisi</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($latestReports as $rep)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-indigo-600 text-sm">{{ $rep['kode_laporan'] }}</span>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ date('d M Y H:i', strtotime($rep['created_at'])) }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $rep['nama_pelapor'] }}</div>
                                        <div class="text-xs text-slate-400">{{ $rep['kontak_pelapor'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ $rep['laboratory']['nama_lab'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-slate-900">{{ $rep['facility']['nama_fasilitas'] ?? '-' }}</span>
                                        @if(! empty($rep['nomor_pc']))
                                            <div class="text-xs text-indigo-600 font-semibold mt-0.5">Unit: {{ $rep['nomor_pc'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(! empty($rep['technician']))
                                            <span class="font-bold text-slate-900">{{ $rep['technician']['nama'] }}</span>
                                        @else
                                            <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">Belum Ditugaskan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $st = $rep['status'];
                                            $badge = match($st) {
                                                'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'ditolak'  => 'bg-rose-100 text-rose-800 border-rose-200',
                                                default    => 'bg-slate-100 text-slate-800 border-slate-200',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badge }} uppercase border">
                                            {{ $st }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.complaints.show', $rep['id']) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-xl text-xs font-bold transition">
                                            Kelola &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">
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
