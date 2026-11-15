<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Data Fasilitas Laboratorium') }}
            </h2>
            <a href="{{ route('admin.facilities.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-wider hover:bg-indigo-700">
                + Tambah Fasilitas
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

            <!-- Filter Fasilitas -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('admin.facilities.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Cari Nama Fasilitas</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: PC-01, Monitor Dell..." class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Laboratorium</label>
                        <select name="lab_id" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Lab</option>
                            @foreach($labs as $l)
                                <option value="{{ $l->id }}" {{ request('lab_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_lab }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase">Kondisi</label>
                        <select name="kondisi" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kondisi</option>
                            <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ request('kondisi') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.facilities.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama Fasilitas / Unit</th>
                                <th class="px-6 py-3 text-left">Laboratorium</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Kondisi</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($facilities as $fac)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $fac->nama_fasilitas }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $fac->lab->nama_lab ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $fac->category->nama_kategori ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($fac->kondisi === 'baik')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Baik</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">Rusak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.facilities.edit', $fac) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                        <form method="POST" action="{{ route('admin.facilities.destroy', $fac) }}" class="inline-block" onsubmit="return confirm('Hapus unit fasilitas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-400">Tidak ada fasilitas ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($facilities->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $facilities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
