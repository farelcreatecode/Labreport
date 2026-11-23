<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Data Master Laboratorium
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Daftar ruang lab komputer yang terdaftar dalam sistem pengaduan</p>
            </div>
            <a href="{{ route('admin.labs.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 transition flex items-center gap-1.5 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Lab Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-2xl shadow-sm text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Nama Lab</th>
                                <th class="px-6 py-4">Lokasi Gedung / Ruang</th>
                                <th class="px-6 py-4 text-center">Kapasitas</th>
                                <th class="px-6 py-4 text-center">Fasilitas</th>
                                <th class="px-6 py-4 text-center">Laporan Masuk</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($laboratories as $lab)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4 font-bold text-slate-900">{{ $lab['nama_lab'] }}</td>
                                    <td class="px-6 py-4">{{ $lab['lokasi'] }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $lab['kapasitas'] ?? '-' }} Unit</td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $lab['facilities_count'] ?? 0 }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-indigo-600">{{ $lab['reports_count'] ?? 0 }}</td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.labs.edit', $lab['id']) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 rounded-xl text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.labs.destroy', $lab['id']) }}" class="inline-block" onsubmit="return confirm('Hapus lab ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data laboratorium.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
