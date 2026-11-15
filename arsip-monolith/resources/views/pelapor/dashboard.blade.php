<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Pelapor') }}
            </h2>
            <a href="{{ route('complaints.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Buat Laporan Kerusakan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500">Total Laporan Saya</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $counts['total'] }}</div>
                </div>

                <!-- Menunggu -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500">
                    <div class="text-sm font-medium text-gray-500">Menunggu Verifikasi</div>
                    <div class="mt-2 text-3xl font-bold text-amber-600">{{ $counts['pending'] }}</div>
                </div>

                <!-- Diproses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500">Sedang Diproses</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600">{{ $counts['diproses'] }}</div>
                </div>

                <!-- Selesai -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <div class="text-sm font-medium text-gray-500">Laporan Selesai</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-600">{{ $counts['selesai'] }}</div>
                </div>
            </div>

            <!-- 5 Laporan Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">5 Laporan Terakhir Anda</h3>
                    <a href="{{ route('complaints.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Lihat Semua Riwayat &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">No. Laporan</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Laboratorium</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Kategori</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Nomor PC</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                <th class="px-6 py-3 text-right font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($recentComplaints as $complaint)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono font-semibold text-indigo-600">
                                        {{ $complaint->report_number }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">{{ $complaint->lab->nama_lab ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $complaint->category->nama_kategori ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $complaint->nomor_pc ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($complaint->status === 'pending')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Menunggu</span>
                                        @elseif($complaint->status === 'diproses')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                                        @elseif($complaint->status === 'selesai')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Selesai</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $complaint->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('complaints.show', $complaint) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-semibold">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada laporan kerusakan yang diajukan.
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
