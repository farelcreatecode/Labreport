<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Semua Laporan Kerusakan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Card -->
            <div class="bg-white p-5 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('admin.complaints.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Cari Laporan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Lap, Nama, NIM, Masalah..." class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                        <select name="lab_id" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Lab</option>
                            @foreach($labs as $l)
                                <option value="{{ $l->id }}" {{ request('lab_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_lab }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Kategori</label>
                        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ (request('category_id') == $c->id || strtolower(request('category')) === strtolower($c->nama_kategori)) ? 'selected' : '' }}>{{ $c->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.complaints.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-md">
                            Reset
                        </a>
                    </div>
                </form>

                @if(Auth::user()->isTechnician())
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-4 text-xs">
                        <span class="font-semibold text-gray-600">Filter Cepat:</span>
                        <a href="{{ route('admin.complaints.index', ['my_tasks' => 1]) }}" class="px-2 py-1 {{ request('my_tasks') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded font-medium">
                            Tugas Ditugaskan ke Saya
                        </a>
                    </div>
                @endif
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">No. Laporan</th>
                                <th class="px-4 py-3 text-left">Pelapor</th>
                                <th class="px-4 py-3 text-left">Lab & Fasilitas</th>
                                <th class="px-4 py-3 text-left">Deskripsi</th>
                                <th class="px-4 py-3 text-left">Teknisi</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($complaints as $complaint)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono font-bold text-indigo-600 whitespace-nowrap">
                                        {{ $complaint->report_number }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $complaint->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $complaint->user->nim_nip ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $complaint->lab->nama_lab ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $complaint->category->nama_kategori ?? '-' }} {{ $complaint->nomor_pc ? '('.$complaint->nomor_pc.')' : '' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $complaint->deskripsi_masalah }}">
                                        {{ \Illuminate\Support\Str::limit($complaint->deskripsi_masalah, 35) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($complaint->technician)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700">
                                                {{ $complaint->technician->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($complaint->status === 'pending')
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Menunggu</span>
                                        @elseif($complaint->status === 'diproses')
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                                        @elseif($complaint->status === 'selesai')
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Selesai</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ $complaint->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="inline-flex px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded text-xs font-semibold">
                                            Kelola
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                        Tidak ditemukan laporan kerusakan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($complaints->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $complaints->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
