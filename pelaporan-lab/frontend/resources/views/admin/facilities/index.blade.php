<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Data Master Fasilitas & Unit
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Inventaris perangkat komputer, monitor, jaringan, dan software laboratorium</p>
            </div>
            <a href="{{ route('admin.facilities.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 transition flex items-center gap-1.5 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Fasilitas</span>
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
                                <th class="px-6 py-4">Nama Fasilitas / Perangkat</th>
                                <th class="px-6 py-4">Ruang Laboratorium</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4 text-center">Kondisi</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($facilities as $fac)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        {{ $fac['nama_fasilitas'] }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ $fac['laboratory']['nama_lab'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100 uppercase">
                                            {{ $fac['kategori'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $cond = $fac['kondisi'];
                                            $badge = match($cond) {
                                                'baik'          => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'rusak_ringan'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'rusak_berat'   => 'bg-rose-100 text-rose-800 border-rose-200',
                                                default         => 'bg-slate-100 text-slate-800 border-slate-200',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badge }} uppercase border">
                                            {{ str_replace('_', ' ', $cond) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.facilities.edit', $fac['id']) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 rounded-xl text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.facilities.destroy', $fac['id']) }}" class="inline-block" onsubmit="return confirm('Hapus fasilitas ini?')">
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
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data fasilitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
