<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="nim_nip" class="block text-sm font-medium text-gray-700">NIM / NIP / Username <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                            <button type="button" id="btn-sync-unuja" onclick="syncFromUnuja()" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Tarik Data dari SSO UNUJA
                            </button>
                        </div>
                        <input type="text" id="nim_nip" name="nim_nip" value="{{ old('nim_nip') }}" placeholder="Contoh: 2101001 atau NIP" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <div id="sync-status" class="mt-1 text-xs text-gray-500 hidden"></div>
                        <x-input-error :messages="$errors->get('nim_nip')" class="mt-1" />
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role / Hak Akses <span class="text-red-500">*</span></label>
                        <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="mahasiswa" {{ old('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa (Pelapor)</option>
                            <option value="dosen" {{ old('role') === 'dosen' ? 'selected' : '' }}>Dosen (Pelapor)</option>
                            <option value="teknisi" {{ old('role') === 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Pengguna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function syncFromUnuja() {
            const nimInput = document.getElementById('nim_nip');
            const statusDiv = document.getElementById('sync-status');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const roleSelect = document.getElementById('role');
            const nim = nimInput.value.trim();

            if (!nim) {
                alert('Silakan ketikkan NIM atau NIP terlebih dahulu.');
                nimInput.focus();
                return;
            }

            statusDiv.classList.remove('hidden', 'text-red-600', 'text-green-600');
            statusDiv.classList.add('text-indigo-600');
            statusDiv.textContent = 'Menghubungi SSO UNUJA...';

            try {
                const response = await fetch(`/api/portal/lookup?filter=mahasiswa&search=${encodeURIComponent(nim)}`);
                const result = await response.json();

                if (result.success && result.data && result.data.length > 0) {
                    const student = result.data[0];
                    if (student.nama) nameInput.value = student.nama;
                    if (!emailInput.value) {
                        emailInput.value = `${nim.toLowerCase()}@student.unuja.ac.id`;
                    }
                    roleSelect.value = student.role || 'mahasiswa';

                    statusDiv.classList.remove('text-indigo-600');
                    statusDiv.classList.add('text-green-600');
                    statusDiv.textContent = `✓ Data berhasil ditarik: ${student.nama}` + (result.is_mock ? ' (Simulasi Mock)' : '');
                } else {
                    statusDiv.classList.remove('text-indigo-600');
                    statusDiv.classList.add('text-red-600');
                    statusDiv.textContent = 'Data tidak ditemukan di Portal SSO UNUJA.';
                }
            } catch (err) {
                statusDiv.classList.remove('text-indigo-600');
                statusDiv.classList.add('text-red-600');
                statusDiv.textContent = 'Gagal terhubung ke API SSO UNUJA.';
            }
        }
    </script>
</x-app-layout>
