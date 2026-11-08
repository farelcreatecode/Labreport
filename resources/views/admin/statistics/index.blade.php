<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Statistik & Analisis Laporan Kerusakan') }}
            </h2>
            <form method="GET" action="{{ route('admin.statistics.index') }}" class="flex items-center space-x-2">
                <label for="year" class="text-xs font-semibold text-gray-500 uppercase">Tahun:</label>
                <select id="year" name="year" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @for($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- 1. Grafik Laporan Per Bulan -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">Tren Laporan Masuk Tahun {{ $year }}</h3>
                <div class="relative h-72">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 2. Laporan Per Laboratorium -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-base font-bold text-gray-900">Jumlah Laporan Per Laboratorium</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                                <tr>
                                    <th class="px-5 py-3 text-left">Nama Laboratorium</th>
                                    <th class="px-5 py-3 text-right">Total Laporan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($perLabData as $lab)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-3 font-medium text-gray-800">{{ $lab['nama_lab'] }}</td>
                                        <td class="px-5 py-3 text-right font-bold text-indigo-600">{{ $lab['total'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-5 py-4 text-center text-gray-400">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Laporan Per Kategori Fasilitas -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-base font-bold text-gray-900">Jumlah Laporan Per Kategori Fasilitas</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                                <tr>
                                    <th class="px-5 py-3 text-left">Kategori Komponen</th>
                                    <th class="px-5 py-3 text-right">Total Laporan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($perCategoryData as $cat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-3 font-medium text-gray-800">{{ $cat['nama_kategori'] }}</td>
                                        <td class="px-5 py-3 text-right font-bold text-indigo-600">{{ $cat['total'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-5 py-4 text-center text-gray-400">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js for Monthly Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthlyData = @json($monthlyData);
            const labels = monthlyData.map(item => item.month_name);
            const data = monthlyData.map(item => item.count);

            const ctx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: data,
                        backgroundColor: '#4F46E5',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
