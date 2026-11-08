<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Fasilitas Laboratorium') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('admin.facilities.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="lab_id" class="block text-sm font-medium text-gray-700">Laboratorium <span class="text-red-500">*</span></label>
                        <select id="lab_id" name="lab_id" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($labs as $l)
                                <option value="{{ $l->id }}" {{ old('lab_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_lab }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('lab_id')" class="mt-1" />
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori Fasilitas <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                    </div>

                    <div>
                        <label for="nama_fasilitas" class="block text-sm font-medium text-gray-700">Nama Fasilitas / Unit <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_fasilitas" name="nama_fasilitas" value="{{ old('nama_fasilitas') }}" required placeholder="Contoh: PC Client 01 / Monitor Samsung 24 Inch" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('nama_fasilitas')" class="mt-1" />
                    </div>

                    <div>
                        <label for="kondisi" class="block text-sm font-medium text-gray-700">Kondisi Fasilitas <span class="text-red-500">*</span></label>
                        <select id="kondisi" name="kondisi" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="baik" {{ old('kondisi') === 'baik' ? 'selected' : '' }}>Baik (Normal Operasional)</option>
                            <option value="rusak" {{ old('kondisi') === 'rusak' ? 'selected' : '' }}>Rusak (Perlu Perbaikan / Penggantian)</option>
                        </select>
                        <x-input-error :messages="$errors->get('kondisi')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Fasilitas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
