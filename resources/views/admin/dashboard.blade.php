<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }} &mdash; <span class="capitalize text-indigo-600">{{ Auth::user()->role }}</span>
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.complaints.index') }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-700">
                    Buka Kelola Laporan
                </a>
                <a href="{{ route('admin.statistics.index') }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-200">
                    Lihat Statistik Lengkap
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(Auth::user()->isTechnician() && $myAssignedCount !== null)
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-900 rounded shadow-sm flex items-center justify-between">
                    <div>
                        <span class="font-bold">Info Tugas Anda:</span> Terdapat <strong>{{ $myAssignedCount }}</strong> laporan yang ditugaskan kepada Anda dan perlu penanganan.
                    </div>
                    <a href="{{ route('admin.complaints.index', ['my_tasks' => 1]) }}" class="text-xs font-bold underline hover:text-blue-700">
                        Lihat Tugas Saya &rarr;
                    </a>
                </div>
            @endif

            <!-- Category Summary Cards: Software, Hardware, Jaringan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Total Laporan Software -->
                <a href="{{ route('admin.complaints.index', ['category' => 'software']) }}" class="group bg-white p-5 rounded-xl shadow-sm border-l-4 border-purple-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Laporan Software</div>
                            <div class="mt-2 text-3xl font-extrabold text-purple-600 group-hover:text-purple-700">
                                {{ $categoryCounts['software'] }}
                            </div>
                            <div class="mt-1 text-xs text-gray-400">Aplikasi, OS & Sistem</div>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-100 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Total Laporan Hardware -->
                <a href="{{ route('admin.complaints.index', ['category' => 'hardware']) }}" class="group bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Laporan Hardware</div>
                            <div class="mt-2 text-3xl font-extrabold text-blue-600 group-hover:text-blue-700">
                                {{ $categoryCounts['hardware'] }}
                            </div>
                            <div class="mt-1 text-xs text-gray-400">PC, Monitor & Periferal</div>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-100 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Total Laporan Jaringan -->
                <a href="{{ route('admin.complaints.index', ['category' => 'jaringan']) }}" class="group bg-white p-5 rounded-xl shadow-sm border-l-4 border-emerald-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Laporan Jaringan</div>
                            <div class="mt-2 text-3xl font-extrabold text-emerald-600 group-hover:text-emerald-700">
                                {{ $categoryCounts['jaringan'] }}
                            </div>
                            <div class="mt-1 text-xs text-gray-400">Internet, Router & Switch</div>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-100 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Content Grid: Chart & Latest Reports -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Doughnut Chart Section -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-800">Distribusi Kategori Laporan</h3>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-gray-100 text-gray-600 rounded">Total: {{ $categoryCounts['total'] }}</span>
                    </div>
                    <div class="relative h-64 flex items-center justify-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-2 text-xs text-gray-600 text-center">
                        <div class="p-2 bg-purple-50 rounded"><span class="block font-bold text-purple-700 text-sm">{{ $categoryCounts['software'] }}</span> Software</div>
                        <div class="p-2 bg-blue-50 rounded"><span class="block font-bold text-blue-700 text-sm">{{ $categoryCounts['hardware'] }}</span> Hardware</div>
                        <div class="p-2 bg-emerald-50 rounded"><span class="block font-bold text-emerald-700 text-sm">{{ $categoryCounts['jaringan'] }}</span> Jaringan</div>
                    </div>
                </div>

                <!-- 5 Recent Reports -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-800">5 Laporan Kerusakan Terbaru</h3>
                        <a href="{{ route('admin.complaints.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            Semua Laporan &rarr;
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">No.</th>
                                    <th class="px-4 py-3 text-left">Pelapor</th>
                                    <th class="px-4 py-3 text-left">Lab / Fasilitas</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentComplaints as $c)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-mono font-bold text-indigo-600">{{ $c->report_number }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">{{ $c->user->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $c->user->nim_nip ?? '' }} ({{ $c->user->role ?? '' }})</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-gray-900">{{ $c->lab->nama_lab ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $c->category->nama_kategori ?? '' }} {{ $c->nomor_pc ? '('.$c->nomor_pc.')' : '' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($c->status === 'pending')
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Menunggu</span>
                                            @elseif($c->status === 'diproses')
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                                            @elseif($c->status === 'selesai')
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Selesai</span>
                                            @else
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.complaints.show', $c) }}" class="inline-flex px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded text-xs font-semibold">
                                                Kelola
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada laporan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script for Doughnut Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            const canvas = document.getElementById('categoryChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            data: chartData.data,
                            backgroundColor: chartData.colors,
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        cutout: '65%'
                    }
                });
            }
        });
    </script>
</x-app-layout>
