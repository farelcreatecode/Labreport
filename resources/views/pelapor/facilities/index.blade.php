<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Fasilitas Laboratorium') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Kategori -->
            <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-semibold text-gray-700">Filter Kategori:</span>
                    <form method="GET" action="{{ route('facilities.index') }}" class="flex items-center space-x-2">
                        <select name="category_id" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @if($selectedCategoryId)
                            <a href="{{ route('facilities.index') }}" class="text-xs text-indigo-600 hover:underline">Reset</a>
                        @endif
                    </form>
                </div>

                <!-- Ringkasan Unit -->
                <div class="flex items-center space-x-4 text-sm">
                    <span class="text-gray-600">Total Unit: <strong class="text-gray-900">{{ $facilityStats['total_units'] }}</strong></span>
                    <span class="text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full font-medium">Baik: {{ $facilityStats['total_baik'] }}</span>
                    <span class="text-red-700 bg-red-50 px-2.5 py-1 rounded-full font-medium">Rusak: {{ $facilityStats['total_rusak'] }}</span>
                </div>
            </div>

            <!-- Rekapitulasi Per Kategori Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($facilityStats['by_category'] as $cat)
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                        <div class="text-xs text-gray-500 uppercase font-semibold">{{ $cat->nama_kategori }}</div>
                        <div class="mt-2 text-2xl font-bold text-gray-800">{{ $cat->total_units }} <span class="text-xs font-normal text-gray-500">unit</span></div>
                        <div class="mt-2 flex justify-center space-x-2 text-xs">
                            <span class="text-emerald-600 font-semibold">{{ $cat->baik_units }} Baik</span>
                            <span class="text-gray-300">|</span>
                            <span class="text-red-600 font-semibold">{{ $cat->rusak_units }} Rusak</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Rincian Fasilitas Per Laboratorium -->
            <div class="space-y-6">
                @foreach($facilityStats['by_lab'] as $lab)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $lab->nama_lab }}</h3>
                                <p class="text-xs text-gray-500">{{ $lab->lokasi }} &bull; Kepala Lab: {{ $lab->kepala_lab ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-white border border-gray-200 rounded text-gray-700">
                                {{ $lab->facilities->count() }} Fasilitas
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama Fasilitas / Unit</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Kategori</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Kondisi Saat Ini</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($lab->facilities as $fac)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3 font-medium text-gray-900">{{ $fac->nama_fasilitas }}</td>
                                            <td class="px-6 py-3 text-gray-600">{{ $fac->category->nama_kategori ?? '-' }}</td>
                                            <td class="px-6 py-3">
                                                @if($fac->kondisi === 'baik')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                        ✓ Baik
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        ✗ Rusak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3 text-right">
                                                @if($fac->kondisi === 'rusak')
                                                    <a href="{{ route('complaints.create') }}?lab_id={{ $lab->id }}&category_id={{ $fac->category_id }}" class="text-xs text-indigo-600 hover:underline font-semibold">
                                                        Laporkan Kendala
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400">Normal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">
                                                Tidak ada fasilitas terdaftar untuk laboratorium ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
