<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pelaporan Kerusakan Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8">
                <form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Pelapor Info (Otomatis dari Auth) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pelapor</label>
                            <div class="mt-1 font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">NIM / NIP</label>
                            <div class="mt-1 font-semibold text-gray-900">{{ Auth::user()->nim_nip ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Pilihan Laboratorium -->
                    <div>
                        <label for="lab_id" class="block text-sm font-medium text-gray-700">
                            Laboratorium Komputer <span class="text-red-500">*</span>
                        </label>
                        <select id="lab_id" name="lab_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_lab }} ({{ $lab->lokasi }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('lab_id')" class="mt-2" />
                    </div>

                    <!-- Jenis Fasilitas / Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">
                            Jenis Fasilitas / Komponen <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">-- Pilih Kategori Fasilitas --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <!-- Nomor PC (Opsional) -->
                    <div>
                        <label for="nomor_pc" class="block text-sm font-medium text-gray-700">
                            Nomor PC / Unit <span class="text-xs text-gray-400 font-normal">(Opsional, contoh: PC-05)</span>
                        </label>
                        <input type="text" id="nomor_pc" name="nomor_pc" value="{{ old('nomor_pc') }}" placeholder="Contoh: PC-01 atau Meja 3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                        <x-input-error :messages="$errors->get('nomor_pc')" class="mt-2" />
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div>
                        <label for="deskripsi_masalah" class="block text-sm font-medium text-gray-700">
                            Deskripsi Masalah / Kerusakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4" required placeholder="Jelaskan secara rinci kerusakan atau kendala yang dialami..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('deskripsi_masalah') }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi_masalah')" class="mt-2" />
                    </div>

                    <!-- Foto Bukti Kerusakan -->
                    <div>
                        <label for="foto_bukti" class="block text-sm font-medium text-gray-700">
                            Foto Bukti Kerusakan <span class="text-xs text-gray-400 font-normal">(Opsional, JPG/PNG, maks. 2MB)</span>
                        </label>
                        <input type="file" id="foto_bukti" name="foto_bukti" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        <x-input-error :messages="$errors->get('foto_bukti')" class="mt-2" />
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
