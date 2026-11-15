<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Semua Laporan Kerusakan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Card -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.complaints.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Cari Laporan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode Tiket, Nama, No. PC, Gejala..." class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2" />
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2 bg-white">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <!-- Filter Lab -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Laboratorium</label>
                        <select name="laboratory_id" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2 bg-white">
                            <option value="">Semua Lab</option>
                            @foreach($laboratories as $l)
                                <option value="{{ $l['id'] }}" {{ request('laboratory_id') == $l['id'] ? 'selected' : '' }}>{{ $l['nama_lab'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Kategori</label>
                        <select name="kategori" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2 bg-white">
                            <option value="">Semua Kategori</option>
                            <option value="hardware" {{ request('kategori') === 'hardware' ? 'selected' : '' }}>Hardware</option>
                            <option value="software" {{ request('kategori') === 'software' ? 'selected' : '' }}>Software</option>
                            <option value="jaringan" {{ request('kategori') === 'jaringan' ? 'selected' : '' }}>Jaringan</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.complaints.index') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Laporan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-400 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">Kode Tiket</th>
                                <th class="px-6 py-3.5">Pelapor & Kontak</th>
                                <th class="px-6 py-3.5">Laboratorium</th>
                                <th class="px-6 py-3.5">Fasilitas / Unit</th>
                                <th class="px-6 py-3.5">Teknisi Bertugas</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($reports as $rep)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                        {{ $rep['kode_laporan'] }}
                                        <div class="text-xs font-normal text-gray-400">{{ date('d M Y H:i', strtotime($rep['created_at'])) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $rep['nama_pelapor'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $rep['kontak_pelapor'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        {{ $rep['laboratory']['nama_lab'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $rep['facility']['nama_fasilitas'] ?? '-' }}</div>
                                        @if(! empty($rep['nomor_pc']))
                                            <span class="text-xs text-indigo-600 font-medium">Unit: {{ $rep['nomor_pc'] }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(! empty($rep['technician']))
                                            <span class="font-semibold text-gray-900">{{ $rep['technician']['nama'] }}</span>
                                        @else
                                            <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded">Belum Ditugaskan</span>
                                        @endif
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
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badge }} uppercase">
                                            {{ $st }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.complaints.show', $rep['id']) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                            Kelola &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400 italic">
                                        Tidak ada laporan kerusakan yang sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Info -->
                <div class="p-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between text-xs text-gray-500">
                    <div>
                        Menampilkan <strong>{{ count($reports) }}</strong> dari <strong>{{ $pagination['total'] ?? 0 }}</strong> total laporan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
