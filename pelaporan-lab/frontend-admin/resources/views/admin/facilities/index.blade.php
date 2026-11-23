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
                                <th class="px-6 py-4 text-center">QR Code Unit</th>
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
                                            $qrUrl = url('/lapor') . '?lab_id=' . ($fac['laboratory_id'] ?? '') . '&facility_id=' . $fac['id'];
                                            $qrImage = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrUrl);
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badge }} uppercase border">
                                            {{ str_replace('_', ' ', $cond) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center" x-data="{ showQr: false }">
                                        <button type="button" @click="showQr = true" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 text-xs font-bold transition">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                            </svg>
                                            <span>Lihat QR</span>
                                        </button>

                                        <!-- Modal QR Code -->
                                        <div x-show="showQr" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                                            <div @click.away="showQr = false" class="bg-white rounded-3xl p-6 max-w-sm w-full text-center space-y-4 shadow-2xl border border-slate-100">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stiker QR Perangkat</span>
                                                    <button @click="showQr = false" class="text-slate-400 hover:text-slate-600 text-sm font-bold">&times;</button>
                                                </div>
                                                <div class="p-4 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100">
                                                    <img src="{{ $qrImage }}" alt="QR Code {{ $fac['nama_fasilitas'] }}" class="w-36 h-36 rounded-lg shadow-xs" />
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900 text-sm">{{ $fac['nama_fasilitas'] }}</div>
                                                    <div class="text-xs text-indigo-600 font-semibold">{{ $fac['laboratory']['nama_lab'] ?? 'Lab Komputer' }}</div>
                                                    <p class="text-[11px] text-slate-400 mt-1">Tempel stiker QR ini pada unit PC/fasilitas untuk mempercepat pencarian data barang rusak saat pelaporan.</p>
                                                </div>
                                                <div class="pt-2 flex gap-2">
                                                    <button @click="window.open('{{ $qrImage }}', '_blank')" class="flex-1 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                                        Buka Gambar QR
                                                    </button>
                                                    <button @click="showQr = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
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
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data fasilitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
