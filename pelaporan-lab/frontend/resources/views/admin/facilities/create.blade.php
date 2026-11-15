<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Fasilitas Laboratorium') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100">
                <form method="POST" action="{{ route('admin.facilities.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Ruang Laboratorium <span class="text-red-500">*</span></label>
                        <select name="laboratory_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2 bg-white">
                            <option value="">-- Pilih Ruang Lab --</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab['id'] }}">{{ $lab['nama_lab'] }} ({{ $lab['lokasi'] }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Nama Fasilitas / Perangkat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_fasilitas" required placeholder="Contoh: PC-01 Core i5, Switch Cisco 24 Port" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2 bg-white">
                                <option value="hardware">Hardware</option>
                                <option value="software">Software</option>
                                <option value="jaringan">Jaringan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Kondisi <span class="text-red-500">*</span></label>
                            <select name="kondisi" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2 bg-white">
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                        <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm">
                            Simpan Fasilitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
