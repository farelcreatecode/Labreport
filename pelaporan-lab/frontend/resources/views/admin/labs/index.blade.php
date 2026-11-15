<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Data Laboratorium') }}
            </h2>
            <a href="{{ route('admin.labs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 shadow-sm transition">
                + Tambah Lab
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
                            <th class="px-6 py-3.5">Nama Lab</th>
                            <th class="px-6 py-3.5">Lokasi Gedung / Ruang</th>
                            <th class="px-6 py-3.5 text-center">Kapasitas</th>
                            <th class="px-6 py-3.5 text-center">Fasilitas</th>
                            <th class="px-6 py-3.5 text-center">Laporan</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laboratories as $lab)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $lab['nama_lab'] }}</td>
                                <td class="px-6 py-4">{{ $lab['lokasi'] }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $lab['kapasitas'] ?? '-' }} Unit</td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $lab['facilities_count'] ?? 0 }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-indigo-600">{{ $lab['reports_count'] ?? 0 }}</td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.labs.edit', $lab['id']) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                    <form method="POST" action="{{ route('admin.labs.destroy', $lab['id']) }}" class="inline-block" onsubmit="return confirm('Hapus lab ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">Belum ada data laboratorium.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
