<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Laboratorium') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('admin.labs.update', $lab) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nama_lab" class="block text-sm font-medium text-gray-700">Nama Laboratorium <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_lab" name="nama_lab" value="{{ old('nama_lab', $lab->nama_lab) }}" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('nama_lab')" class="mt-1" />
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi / Gedung <span class="text-red-500">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $lab->lokasi) }}" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('lokasi')" class="mt-1" />
                    </div>

                    <div>
                        <label for="kepala_lab" class="block text-sm font-medium text-gray-700">Kepala Laboratorium</label>
                        <input type="text" id="kepala_lab" name="kepala_lab" value="{{ old('kepala_lab', $lab->kepala_lab) }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('kepala_lab')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.labs.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700">Perbarui Lab</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
