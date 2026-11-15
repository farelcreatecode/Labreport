<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Status Laporan - LabReport</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('pelapor.create') }}" class="flex items-center gap-2 text-indigo-600 font-bold text-lg">
                &larr; <span class="text-gray-900">LabReport</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('pelapor.create') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    + Buat Laporan Baru
                </a>
            </div>
        </div>
    </header>

    <main class="py-10 px-4 sm:px-6 lg:px-8 flex-1">
        <div class="max-w-3xl mx-auto space-y-6">

            <!-- Search Box -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Lacak Status Penanganan Laporan</h2>
                <p class="text-xs text-gray-500 mb-4">Masukkan Nomor Registrasi / Kode Laporan Anda (contoh: #LR-202611-0001)</p>

                <form method="GET" action="{{ route('pelapor.track') }}" class="flex gap-2">
                    <input type="text" name="kode" value="{{ $kode }}" required placeholder="Contoh: #LR-202611-0001" class="flex-1 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-2.5 border uppercase font-mono" />
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition">
                        Cari Tiket
                    </button>
                </form>

                @if(session('success'))
                    <div class="mt-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Detail Laporan jika ditemukan -->
            @if($report)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-200 gap-4">
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nomor Laporan</span>
                            <div class="text-2xl font-mono font-bold text-indigo-700">{{ $report['kode_laporan'] }}</div>
                        </div>

                        <div>
                            @php
                                $status = $report['status'];
                                $badgeClass = match($status) {
                                    'pending'  => 'bg-amber-100 text-amber-800',
                                    'diproses' => 'bg-blue-100 text-blue-800',
                                    'selesai'  => 'bg-emerald-100 text-emerald-800',
                                    'ditolak'  => 'bg-red-100 text-red-800',
                                    default    => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-4 py-1.5 text-xs font-bold rounded-full uppercase {{ $badgeClass }}">
                                {{ $status === 'pending' ? 'Menunggu Konfirmasi' : ucfirst($status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Grid Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl">
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-semibold">Pelapor</span>
                            <div class="font-bold text-gray-900">{{ $report['nama_pelapor'] }}</div>
                            <div class="text-xs text-gray-500">{{ $report['kontak_pelapor'] }}</div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-semibold">Waktu Dilaporkan</span>
                            <div class="text-sm font-semibold text-gray-800">{{ date('d M Y H:i', strtotime($report['created_at'])) }} WIB</div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-semibold">Ruang Laboratorium</span>
                            <div class="font-bold text-gray-900">{{ $report['laboratory']['nama_lab'] ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $report['laboratory']['lokasi'] ?? '' }}</div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-semibold">Perangkat / Unit PC</span>
                            <div class="font-bold text-gray-900">{{ $report['facility']['nama_fasilitas'] ?? '-' }}</div>
                            <div class="text-xs text-indigo-600 font-medium">Nomor PC: {{ $report['nomor_pc'] ?? 'Tidak Ditentukan' }}</div>
                        </div>
                    </div>

                    <!-- Teknisi Bertugas -->
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-indigo-500 uppercase font-semibold">Teknisi Penanggung Jawab</span>
                            <div class="text-base font-bold text-indigo-950">
                                {{ $report['technician']['nama'] ?? 'Belum Ditugaskan' }}
                            </div>
                            @if(isset($report['technician']['spesialisasi']))
                                <div class="text-xs text-indigo-600">{{ $report['technician']['spesialisasi'] }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</h4>
                        <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                            {{ $report['deskripsi_masalah'] }}
                        </div>
                    </div>

                    <!-- Tanggapan & Tindakan Penanganan -->
                    @if(! empty($report['tanggapan']))
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan Penanganan & Tindakan</h4>
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-950 whitespace-pre-line leading-relaxed">
                                {{ $report['tanggapan'] }}
                            </div>
                        </div>
                    @endif

                    <!-- Timeline Audit Trail -->
                    @if(! empty($report['histories']))
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Riwayat Perjalanan Laporan</h4>
                            <div class="space-y-4">
                                @foreach($report['histories'] as $history)
                                    <div class="flex items-start gap-3">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 mt-1.5 shrink-0"></div>
                                        <div class="flex-1 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span class="font-bold text-gray-800">{{ $history['changed_by'] }}</span>
                                                <span>{{ date('d M Y H:i', strtotime($history['created_at'])) }} WIB</span>
                                            </div>
                                            <div class="text-sm text-gray-700 mt-1">
                                                {{ $history['catatan'] ?: 'Status diubah ke ' . $history['status_to'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} LabReport &bull; Universitas Nurul Jadid
    </footer>

</body>
</html>
