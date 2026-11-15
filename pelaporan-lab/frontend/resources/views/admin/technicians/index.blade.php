<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Data Master Teknisi Lapangan
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Daftar teknisi yang dapat ditugaskan untuk memperbaiki fasilitas</p>
            </div>
            <a href="{{ route('admin.technicians.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 transition flex items-center gap-1.5 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Teknisi Baru</span>
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
                                <th class="px-6 py-4">Nama Teknisi</th>
                                <th class="px-6 py-4">Nomor Kontak / WhatsApp</th>
                                <th class="px-6 py-4">Spesialisasi</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Jumlah Penugasan</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($technicians as $tech)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        {{ $tech['nama'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $tech['kontak'] ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100">
                                            {{ $tech['spesialisasi'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(($tech['status'] ?? 'aktif') === 'aktif')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase border border-emerald-200">Aktif</span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold uppercase border border-slate-200">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-indigo-600">
                                        {{ $tech['reports_count'] ?? 0 }} tiket
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.technicians.edit', $tech['id']) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 rounded-xl text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.technicians.destroy', $tech['id']) }}" class="inline-block" onsubmit="return confirm('Hapus data teknisi ini?')">
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
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">
                                        Belum ada data teknisi lapangan yang terdaftar.
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
