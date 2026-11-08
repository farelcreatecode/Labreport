<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Laporan: <span class="font-mono text-indigo-600">{{ $complaint->report_number }}</span>
            </h2>
            <a href="{{ route('admin.complaints.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Kelola Laporan
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Detail Info Laporan (2 Kolom) -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase">Status Terkini</span>
                            <div class="mt-1">
                                @if($complaint->status === 'pending')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 uppercase">Menunggu</span>
                                @elseif($complaint->status === 'diproses')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 uppercase">Diproses</span>
                                @elseif($complaint->status === 'selesai')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase">Selesai</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 uppercase">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold text-gray-400 uppercase">Waktu Lapor</span>
                            <div class="text-sm text-gray-700 font-medium">{{ $complaint->created_at->format('d M Y H:i') }} WIB</div>
                        </div>
                    </div>

                    <!-- Pelapor Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase">Data Pelapor</div>
                            <div class="font-bold text-gray-900 mt-0.5">{{ $complaint->user->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">NIM/NIP: {{ $complaint->user->nim_nip ?? '-' }} &bull; Role: {{ ucfirst($complaint->user->role ?? '-') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase">Email Kontak</div>
                            <div class="text-sm text-gray-700 mt-0.5">{{ $complaint->user->email ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Lokasi & Fasilitas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase">Laboratorium</div>
                            <div class="text-base font-bold text-gray-900">{{ $complaint->lab->nama_lab ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $complaint->lab->lokasi ?? '' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase">Fasilitas / Unit PC</div>
                            <div class="text-base font-bold text-gray-900">{{ $complaint->category->nama_kategori ?? '-' }}</div>
                            <div class="text-xs text-indigo-600 font-medium">Nomor PC: {{ $complaint->nomor_pc ?? 'Tidak ditentukan' }}</div>
                        </div>
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan Pelapor</div>
                        <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-800 leading-relaxed whitespace-pre-line">
                            {{ $complaint->deskripsi_masalah }}
                        </div>
                    </div>

                    <!-- Foto Bukti -->
                    @if($complaint->foto_bukti)
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Foto Bukti Kerusakan</div>
                            <a href="{{ asset('storage/' . $complaint->foto_bukti) }}" target="_blank" class="inline-block border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/' . $complaint->foto_bukti) }}" alt="Foto Bukti" class="max-h-72 object-contain bg-black/5" />
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Form Tindak Lanjut / Update Status (1 Kolom) -->
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 pb-3 border-b border-gray-200">
                        Tindak Lanjut & Penanganan
                    </h3>

                    @can('update', $complaint)
                        <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Perbarui Status</label>
                                <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="pending" {{ old('status', $complaint->status) === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diproses" {{ old('status', $complaint->status) === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ old('status', $complaint->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak" {{ old('status', $complaint->status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-1" />
                            </div>

                            <!-- Assign Technician (Admin Only) -->
                            @if(Auth::user()->isAdmin())
                                <div>
                                    <label for="technician_id" class="block text-sm font-medium text-gray-700">Tugaskan Teknisi</label>
                                    <select id="technician_id" name="technician_id" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Pilih Teknisi (Opsional) --</option>
                                        @foreach($technicians as $tech)
                                            <option value="{{ $tech->id }}" {{ old('technician_id', $complaint->technician_id) == $tech->id ? 'selected' : '' }}>
                                                {{ $tech->name }} ({{ $tech->nim_nip ?? 'Teknisi' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('technician_id')" class="mt-1" />
                                </div>
                            @else
                                <!-- Jika Teknisi, tampilkan penugasan saja (readonly) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Teknisi Bertugas</label>
                                    <div class="mt-1 text-sm font-semibold text-gray-800">
                                        {{ $complaint->technician->name ?? 'Ditugaskan kepada Anda' }}
                                    </div>
                                </div>
                            @endif

                            <!-- Tanggapan / Solusi -->
                            <div>
                                <label for="tanggapan" class="block text-sm font-medium text-gray-700">
                                    Tanggapan / Catatan Penanganan
                                </label>
                                <textarea id="tanggapan" name="tanggapan" rows="5" placeholder="Tuliskan catatan teknis, tindakan yang telah diambil, atau alasan penolakan..." class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('tanggapan', $complaint->tanggapan) }}</textarea>
                                <x-input-error :messages="$errors->get('tanggapan')" class="mt-1" />
                            </div>

                            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-semibold shadow-sm transition">
                                Simpan Perubahan
                            </button>
                        </form>
                    @else
                        <!-- Notice jika bukan penanggung jawab laporan ini -->
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 leading-relaxed">
                            <strong>Perhatian:</strong> Sebagai teknisi, Anda hanya dapat memperbarui status laporan yang telah ditugaskan secara resmi kepada Anda.
                            <div class="mt-2 font-semibold">
                                Teknisi saat ini: {{ $complaint->technician->name ?? 'Belum ada teknisi ditugaskan' }}
                            </div>
                        </div>

                        <!-- Readonly Tanggapan -->
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase">Tanggapan Saat Ini</div>
                            <div class="mt-2 p-3 bg-gray-50 rounded text-sm text-gray-700">
                                {{ $complaint->tanggapan ?: 'Belum ada tanggapan tercatat.' }}
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
