<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Laboratorium') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100">
                <form method="POST" action="{{ route('admin.labs.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Nama Laboratorium <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lab" required placeholder="Contoh: Lab Jaringan & Cyber Security" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Lokasi / Gedung</label>
                        <input type="text" name="lokasi" placeholder="Contoh: Gedung B, Lantai 1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Kapasitas (Jumlah Unit/Meja)</label>
                        <input type="number" name="kapasitas" min="1" placeholder="30" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2" />
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                        <a href="{{ route('admin.labs.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm">
                            Simpan Laboratorium
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
