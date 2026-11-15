<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Status Tiket &mdash; LabReport</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.25); }
        }
        .animate-beacon {
            animation: pulseGlow 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="font-sans bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white" x-data="{
    copied: false,
    copyCode(text) {
        navigator.clipboard.writeText(text);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }
}">

    <!-- Header Publik -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <a href="{{ route('pelapor.create') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-black text-slate-900 tracking-tight">LabReport</h1>
                    <p class="text-xs text-slate-400">Kembali ke Form Laporan</p>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('pelapor.create') }}" class="px-4 py-2 text-xs sm:text-sm font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl transition">
                    + Buat Laporan Baru
                </a>
            </div>
        </div>
    </header>

    <main class="py-10 px-4 sm:px-6 lg:px-8 flex-1 animate-fade-in">
        <div class="max-w-3xl mx-auto space-y-7">

            <!-- Search Box Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Lacak Status Penanganan Tiket</h2>
                        <p class="text-xs text-slate-400">Masukkan kode registrasi laporan untuk melihat progres teknisi</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('pelapor.track') }}" class="flex flex-col sm:flex-row gap-2.5 pt-2">
                    <div class="relative flex-1">
                        <input type="text" name="kode" value="{{ $kode }}" required placeholder="Contoh: #LR-202611-0001" class="w-full py-3 px-4 rounded-2xl border border-slate-300 text-sm font-mono uppercase tracking-wider focus:border-indigo-600 shadow-xs" />
                    </div>
                    <button type="submit" class="px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-sm shadow-md shadow-indigo-500/20 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Periksa Status</span>
                    </button>
                </form>

                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-xl text-xs leading-relaxed">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-900 rounded-xl text-xs leading-relaxed">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Detail Laporan jika ditemukan -->
            @if($report)
                @php
                    $storageBase = rtrim(env('REPORT_STORAGE_URL', 'http://127.0.0.1:8003/storage'), '/');
                    $status = $report['status'];
                    $badgeClass = match($status) {
                        'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
                        'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'ditolak'  => 'bg-rose-100 text-rose-800 border-rose-200',
                        default    => 'bg-slate-100 text-slate-800 border-slate-200',
                    };
                    $stepIndex = match($status) {
                        'pending'  => 1,
                        'diproses' => 2,
                        'selesai'  => 3,
                        'ditolak'  => 0,
                        default    => 1,
                    };
                @endphp

                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden p-6 sm:p-8 space-y-7">
                    
                    <!-- Header Tiket & Status Badge -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kode Registrasi Laporan</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-2xl font-mono font-black text-indigo-700">{{ $report['kode_laporan'] }}</span>
                                <button type="button" x-on:click="copyCode('{{ $report['kode_laporan'] }}')" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition" title="Salin Kode Tiket">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                    </svg>
                                </button>
                                <span x-show="copied" x-cloak class="text-[11px] font-bold text-emerald-600">Disalin!</span>
                            </div>
                        </div>

                        <div>
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold border {{ $badgeClass }} uppercase tracking-wide">
                                @if($status === 'pending')
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-beacon"></span>
                                    <span>Menunggu Konfirmasi</span>
                                @elseif($status === 'diproses')
                                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-beacon"></span>
                                    <span>Sedang Ditangani Teknisi</span>
                                @elseif($status === 'selesai')
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Selesai Diperbaiki</span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>Laporan Ditolak</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Progress Stepper (Jika bukan ditolak) -->
                    @if($status !== 'ditolak')
                        <div class="py-2">
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $stepIndex >= 1 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-slate-200 text-slate-500' }}">
                                        1
                                    </div>
                                    <div class="text-xs font-bold {{ $stepIndex >= 1 ? 'text-indigo-900' : 'text-slate-400' }}">Laporan Masuk</div>
                                </div>
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $stepIndex >= 2 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-slate-200 text-slate-500' }}">
                                        2
                                    </div>
                                    <div class="text-xs font-bold {{ $stepIndex >= 2 ? 'text-indigo-900' : 'text-slate-400' }}">Teknisi Ditugaskan</div>
                                </div>
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $stepIndex >= 3 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/30' : 'bg-slate-200 text-slate-500' }}">
                                        3
                                    </div>
                                    <div class="text-xs font-bold {{ $stepIndex >= 3 ? 'text-emerald-700' : 'text-slate-400' }}">Selesai Diperbaiki</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Grid Info Rinci -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold">Identitas Pelapor</span>
                            <div class="font-extrabold text-slate-900 mt-0.5">{{ $report['nama_pelapor'] }}</div>
                            <div class="text-xs text-slate-500">{{ $report['kontak_pelapor'] }}</div>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold">Waktu Lapor</span>
                            <div class="text-sm font-bold text-slate-800 mt-0.5">
                                {{ date('d M Y H:i', strtotime($report['created_at'])) }} WIB
                            </div>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold">Laboratorium</span>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $report['laboratory']['nama_lab'] ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $report['laboratory']['lokasi'] ?? '' }}</div>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold">Fasilitas / Unit</span>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $report['facility']['nama_fasilitas'] ?? '-' }}</div>
                            <div class="text-xs text-indigo-600 font-semibold">Nomor PC: {{ $report['nomor_pc'] ?? 'Tidak Ditentukan' }}</div>
                        </div>
                    </div>

                    <!-- Teknisi Penanggung Jawab -->
                    <div class="p-5 bg-indigo-50/70 border border-indigo-100 rounded-2xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-indigo-500 uppercase font-bold">Teknisi Penanggung Jawab</span>
                            <div class="text-base font-extrabold text-indigo-950 mt-0.5">
                                {{ $report['technician']['nama'] ?? 'Belum Ditugaskan' }}
                            </div>
                            @if(isset($report['technician']['spesialisasi']))
                                <div class="text-xs text-indigo-600 mt-0.5">{{ $report['technician']['spesialisasi'] }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Deskripsi Masalah Pelapor -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</h4>
                        <div class="p-4 bg-slate-50 rounded-2xl text-sm text-slate-800 whitespace-pre-line leading-relaxed border border-slate-100">
                            {{ $report['deskripsi_masalah'] }}
                        </div>
                    </div>

                    <!-- Foto Bukti Kerusakan jika ada -->
                    @if(! empty($report['attachments']))
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Bukti Kerusakan</h4>
                            <div class="flex flex-wrap gap-3">
                                @foreach($report['attachments'] as $att)
                                    <a href="{{ $storageBase . '/' . $att['file_path'] }}" target="_blank" class="inline-block border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:opacity-90 transition">
                                        <img src="{{ $storageBase . '/' . $att['file_path'] }}" alt="Bukti Kerusakan" class="max-h-60 object-contain bg-black/5" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tanggapan & Tindakan Petugas -->
                    @if(! empty($report['tanggapan']))
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan Penanganan Petugas</h4>
                            <div class="p-4 bg-emerald-50/80 border border-emerald-200 rounded-2xl text-sm text-emerald-950 whitespace-pre-line leading-relaxed">
                                {{ $report['tanggapan'] }}
                            </div>
                        </div>
                    @endif

                    <!-- Timeline Riwayat -->
                    @if(! empty($report['histories']))
                        <div class="pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Riwayat Aktivitas Penanganan</span>
                            </h4>

                            <div class="space-y-3.5">
                                @foreach($report['histories'] as $history)
                                    <div class="flex items-start gap-3.5">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 mt-1.5 shrink-0 ring-4 ring-indigo-50"></div>
                                        <div class="flex-1 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                                            <div class="flex items-center justify-between text-xs text-slate-500">
                                                <span class="font-bold text-slate-800">{{ $history['changed_by'] }}</span>
                                                <span>{{ date('d M Y H:i', strtotime($history['created_at'])) }} WIB</span>
                                            </div>
                                            <div class="text-sm text-slate-700 mt-1">
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

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} <strong>LabReport</strong> &bull; Universitas Nurul Jadid
    </footer>

</body>
</html>
