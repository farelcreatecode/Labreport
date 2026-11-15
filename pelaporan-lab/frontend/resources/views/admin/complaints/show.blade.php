<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Laporan: <span class="font-mono text-indigo-600">{{ $report['kode_laporan'] }}</span>
            </h2>
            <a href="{{ route('admin.complaints.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Kelola Laporan
            </a>
        </div>
    </x-slot>

    @php
        $storageBase = rtrim(env('REPORT_STORAGE_URL', 'http://127.0.0.1:8003/storage'), '/');
        $status = $report['status'];
        $badgeClass = match($status) {
            'pending'  => 'bg-amber-100 text-amber-800',
            'diproses' => 'bg-blue-100 text-blue-800',
            'selesai'  => 'bg-emerald-100 text-emerald-800',
            'ditolak'  => 'bg-red-100 text-red-800',
            default    => 'bg-gray-100 text-gray-800',
        };
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Detail Laporan & Riwayat Timeline (2 Kolom) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Kartu Info Laporan -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <span class="text-xs font-semibold text-gray-400 uppercase">Status Terkini</span>
                                <div class="mt-1">
                                    <span class="px-3.5 py-1 text-xs font-bold rounded-full uppercase {{ $badgeClass }}">
                                        {{ $status === 'pending' ? 'Menunggu Konfirmasi' : ucfirst($status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold text-gray-400 uppercase">Waktu Lapor</span>
                                <div class="text-sm text-gray-700 font-medium">
                                    {{ date('d M Y H:i', strtotime($report['created_at'])) }} WIB
                                </div>
                            </div>
                        </div>

                        <!-- Data Pelapor Publik (Tanpa Login) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase">Nama Pelapor</div>
                                <div class="font-bold text-gray-900 mt-0.5 text-base">{{ $report['nama_pelapor'] }}</div>
                                <div class="text-xs text-indigo-600 font-medium">Pelapor Publik (Tanpa Akun)</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase">Kontak Pelapor</div>
                                <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $report['kontak_pelapor'] }}</div>
                            </div>
                        </div>

                        <!-- Lokasi & Fasilitas -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase">Laboratorium</div>
                                <div class="text-base font-bold text-gray-900">{{ $report['laboratory']['nama_lab'] ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $report['laboratory']['lokasi'] ?? '' }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase">Fasilitas / Unit</div>
                                <div class="text-base font-bold text-gray-900">{{ $report['facility']['nama_fasilitas'] ?? '-' }}</div>
                                <div class="text-xs text-indigo-600 font-medium">Nomor PC: {{ $report['nomor_pc'] ?? 'Tidak ditentukan' }}</div>
                            </div>
                        </div>

                        <!-- Deskripsi Kerusakan -->
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan Pelapor</div>
                            <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-800 leading-relaxed whitespace-pre-line border border-gray-100">
                                {{ $report['deskripsi_masalah'] }}
                            </div>
                        </div>

                        <!-- Foto Bukti -->
                        @if(! empty($report['attachments']))
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Foto Bukti Kerusakan</div>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($report['attachments'] as $att)
                                        <a href="{{ $storageBase . '/' . $att['file_path'] }}" target="_blank" class="inline-block border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:opacity-90">
                                            <img src="{{ $storageBase . '/' . $att['file_path'] }}" alt="Foto Bukti" class="max-h-72 object-contain bg-black/5" />
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Kartu Timeline Riwayat Laporan (Audit Trail) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-4">
                        <h3 class="text-base font-bold text-gray-900 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Riwayat Perubahan Status & Penugasan</span>
                        </h3>

                        <div class="space-y-4 pt-2">
                            @forelse($report['histories'] ?? [] as $hist)
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600 mt-1.5 shrink-0 ring-4 ring-indigo-50"></div>
                                    <div class="flex-1 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-bold text-gray-900">{{ $hist['changed_by'] }}</span>
                                            <span class="text-gray-400">{{ date('d M Y H:i', strtotime($hist['created_at'])) }} WIB</span>
                                        </div>
                                        <div class="text-sm text-gray-700 mt-1">
                                            {{ $hist['catatan'] ?: 'Status diubah menjadi ' . $hist['status_to'] }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic">Belum ada riwayat aktivitas penanganan tercatat.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Form Tindak Lanjut & Penugasan Teknisi (1 Kolom) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6 h-fit sticky top-24">
                    <h3 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">
                        Tindak Lanjut & Penugasan
                    </h3>

                    <form method="POST" action="{{ route('admin.complaints.update', $report['id']) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700">Perbarui Status</label>
                            <select id="status" name="status" required class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2 bg-white">
                                <option value="pending" {{ $report['status'] === 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                                <option value="diproses" {{ $report['status'] === 'diproses' ? 'selected' : '' }}>Diproses (Sedang Diperbaiki)</option>
                                <option value="selesai" {{ $report['status'] === 'selesai' ? 'selected' : '' }}>Selesai (Tuntas Diperbaiki)</option>
                                <option value="ditolak" {{ $report['status'] === 'ditolak' ? 'selected' : '' }}>Ditolak (Tidak Sesuai)</option>
                            </select>
                        </div>

                        <!-- Tugaskan Teknisi (Dropdown dari tabel technicians) -->
                        <div>
                            <label for="technician_id" class="block text-sm font-semibold text-gray-700">Tugaskan Teknisi Lapangan</label>
                            <select id="technician_id" name="technician_id" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2 bg-white">
                                <option value="">-- Pilih Teknisi (Opsional) --</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech['id'] }}" {{ ($report['technician_id'] ?? null) == $tech['id'] ? 'selected' : '' }}>
                                        {{ $tech['nama'] }} ({{ $tech['spesialisasi'] }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Teknisi dipilih oleh admin melalui data master teknisi.</p>
                        </div>

                        <!-- Tanggapan / Catatan Penanganan -->
                        <div>
                            <label for="tanggapan" class="block text-sm font-semibold text-gray-700">
                                Catatan / Hasil Perbaikan
                            </label>
                            <textarea id="tanggapan" name="tanggapan" rows="5" placeholder="Tuliskan tindakan teknisi, komponen yang diganti, atau alasan penolakan..." class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">{{ old('tanggapan', $report['tanggapan']) }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold shadow-md shadow-indigo-100 transition">
                            Simpan Perubahan Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
