<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Laporan: <span class="text-indigo-600 font-mono">{{ $complaint->report_number }}</span>
            </h2>
            <a href="{{ route('complaints.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8 space-y-6">
                <!-- Header Status -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-200">
                    <div>
                        <div class="text-sm text-gray-500">Nomor Registrasi Laporan</div>
                        <div class="text-2xl font-mono font-bold text-indigo-700">{{ $complaint->report_number }}</div>
                    </div>
                    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-500">Status Saat Ini:</span>
                        @if($complaint->status === 'pending')
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-amber-100 text-amber-800 uppercase">Menunggu</span>
                        @elseif($complaint->status === 'diproses')
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-blue-100 text-blue-800 uppercase">Diproses</span>
                        @elseif($complaint->status === 'selesai')
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase">Selesai</span>
                        @else
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-red-100 text-red-800 uppercase">Ditolak</span>
                        @endif
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Lokasi Laboratorium</span>
                        <span class="text-base font-semibold text-gray-800">{{ $complaint->lab->nama_lab ?? '-' }}</span>
                        <p class="text-xs text-gray-500">{{ $complaint->lab->lokasi ?? '' }}</p>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Fasilitas / Komponen</span>
                        <span class="text-base font-semibold text-gray-800">{{ $complaint->category->nama_kategori ?? '-' }}</span>
                        @if($complaint->nomor_pc)
                            <p class="text-xs text-indigo-600 font-medium">Unit: {{ $complaint->nomor_pc }}</p>
                        @endif
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Waktu Pelaporan</span>
                        <span class="text-sm text-gray-700">{{ $complaint->created_at->isoFormat('D MMMM Y, HH:mm') ?? $complaint->created_at->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Teknisi Penanggung Jawab</span>
                        <span class="text-sm font-semibold {{ $complaint->technician ? 'text-gray-900' : 'text-gray-400 italic' }}">
                            {{ $complaint->technician->name ?? 'Belum ditugaskan' }}
                        </span>
                    </div>
                </div>

                <!-- Deskripsi Kerusakan -->
                <div class="pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</h4>
                    <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                        {{ $complaint->deskripsi_masalah }}
                    </div>
                </div>

                <!-- Foto Bukti -->
                @if($complaint->foto_bukti)
                    <div class="pt-4 border-t border-gray-100">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Foto Bukti Kerusakan</h4>
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $complaint->foto_bukti) }}" target="_blank" class="inline-block border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:opacity-90">
                                <img src="{{ asset('storage/' . $complaint->foto_bukti) }}" alt="Foto Bukti" class="max-h-72 object-contain bg-black/5" />
                            </a>
                            <p class="text-xs text-gray-400 mt-1">Klik gambar untuk melihat resolusi penuh.</p>
                        </div>
                    </div>
                @endif

                <!-- Tanggapan Teknisi / Admin -->
                <div class="pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tanggapan & Progres Perbaikan</h4>
                    @if($complaint->tanggapan)
                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-lg text-sm text-indigo-950 whitespace-pre-line leading-relaxed">
                            {{ $complaint->tanggapan }}
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-400 italic">
                            Belum ada tanggapan atau catatan penanganan dari petugas.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
