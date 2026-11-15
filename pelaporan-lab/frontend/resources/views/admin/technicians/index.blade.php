<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Master Teknisi Lapangan') }}
            </h2>
            <a href="{{ route('admin.technicians.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 shadow-sm transition">
                + Tambah Teknisi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-400 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5">Nama Teknisi</th>
                            <th class="px-6 py-3.5">Nomor Kontak / WA</th>
                            <th class="px-6 py-3.5">Spesialisasi</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Jumlah Tugas</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($technicians as $tech)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $tech['nama'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $tech['kontak'] ?: '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-md text-xs font-medium">
                                        {{ $tech['spesialisasi'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if(($tech['status'] ?? 'aktif') === 'aktif')
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold uppercase">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-semibold text-indigo-600">
                                    {{ $tech['reports_count'] ?? 0 }} laporan
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.technicians.edit', $tech['id']) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.technicians.destroy', $tech['id']) }}" class="inline-block" onsubmit="return confirm('Hapus data teknisi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">
                                    Belum ada data teknisi lapangan yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
