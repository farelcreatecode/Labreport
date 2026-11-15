<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Data Fasilitas Laboratorium') }}
            </h2>
            <a href="{{ route('admin.facilities.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 shadow-sm transition">
                + Tambah Fasilitas
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
                            <th class="px-6 py-3.5">Nama Fasilitas / Perangkat</th>
                            <th class="px-6 py-3.5">Ruang Laboratorium</th>
                            <th class="px-6 py-3.5">Kategori</th>
                            <th class="px-6 py-3.5 text-center">Kondisi</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($facilities as $fac)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $fac['nama_fasilitas'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $fac['laboratory']['nama_lab'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-md text-xs font-medium uppercase">
                                        {{ $fac['kategori'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $cond = $fac['kondisi'];
                                        $badge = match($cond) {
                                            'baik'          => 'bg-emerald-100 text-emerald-800',
                                            'rusak_ringan'  => 'bg-amber-100 text-amber-800',
                                            'rusak_berat'   => 'bg-red-100 text-red-800',
                                            default         => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }} uppercase">
                                        {{ str_replace('_', ' ', $cond) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.facilities.edit', $fac['id']) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                    <form method="POST" action="{{ route('admin.facilities.destroy', $fac['id']) }}" class="inline-block" onsubmit="return confirm('Hapus fasilitas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Belum ada data fasilitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
