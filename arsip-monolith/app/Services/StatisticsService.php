<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Facility;
use App\Models\Lab;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    /**
     * Get complaint status counts.
     *
     * @param int|null $userId Filter by user if provided (e.g. for pelapor dashboard)
     * @return array<string, int>
     */
    public function getStatusCounts(?int $userId = null): array
    {
        $query = Complaint::query();

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        $counts = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'total'    => array_sum($counts),
            'pending'  => $counts['pending'] ?? 0,
            'diproses' => $counts['diproses'] ?? 0,
            'selesai'  => $counts['selesai'] ?? 0,
            'ditolak'  => $counts['ditolak'] ?? 0,
        ];
    }

    /**
     * Get chart data formatted for frontend doughnut chart.
     */
    public function getStatusChartData(?int $userId = null): array
    {
        $counts = $this->getStatusCounts($userId);

        return [
            'labels' => ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'],
            'data'   => [
                $counts['pending'],
                $counts['diproses'],
                $counts['selesai'],
                $counts['ditolak'],
            ],
            'colors' => ['#F59E0B', '#3B82F6', '#10B981', '#EF4444'],
        ];
    }

    /**
     * Get complaint counts by category (Software, Hardware, Jaringan).
     */
    public function getCategoryCounts(?int $userId = null): array
    {
        $categories = Category::withCount(['complaints' => function ($q) use ($userId) {
            if ($userId !== null) {
                $q->where('user_id', $userId);
            }
        }])->get();

        $software = 0;
        $hardware = 0;
        $jaringan = 0;

        foreach ($categories as $cat) {
            $name = strtolower(trim($cat->nama_kategori));
            if (str_contains($name, 'soft')) {
                $software += $cat->complaints_count;
            } elseif (str_contains($name, 'jar')) {
                $jaringan += $cat->complaints_count;
            } else {
                // 'hard' or legacy categories (Komputer, Monitor, Keyboard, Mouse, Printer)
                $hardware += $cat->complaints_count;
            }
        }

        $total = Complaint::when($userId !== null, fn($q) => $q->where('user_id', $userId))->count();

        return [
            'total'    => $total,
            'software' => $software,
            'hardware' => $hardware,
            'jaringan' => $jaringan,
            'by_category' => $categories,
        ];
    }

    /**
     * Get doughnut chart data for categories (Software, Hardware, Jaringan).
     */
    public function getCategoryChartData(?int $userId = null): array
    {
        $counts = $this->getCategoryCounts($userId);

        return [
            'labels' => ['Software', 'Hardware', 'Jaringan'],
            'data'   => [
                $counts['software'],
                $counts['hardware'],
                $counts['jaringan'],
            ],
            'colors' => ['#8B5CF6', '#3B82F6', '#10B981'],
        ];
    }

    /**
     * Get monthly complaint counts for a specific year (default: current year).
     */
    public function getMonthlyComplaintsCount(?int $year = null): array
    {
        $year = $year ?? Carbon::now()->year;

        $monthlyData = Complaint::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[] = [
                'month_number' => $m,
                'month_name'   => $monthNames[$m],
                'count'        => $monthlyData[$m] ?? 0,
            ];
        }

        return $result;
    }

    /**
     * Get complaints count grouped by lab.
     */
    public function getComplaintsPerLab(): array
    {
        return Lab::withCount('complaints')
            ->get()
            ->map(function ($lab) {
                return [
                    'lab_id'     => $lab->id,
                    'nama_lab'   => $lab->nama_lab,
                    'total'      => $lab->complaints_count,
                ];
            })
            ->toArray();
    }

    /**
     * Get complaints count grouped by category.
     */
    public function getComplaintsPerCategory(): array
    {
        return Category::withCount('complaints')
            ->get()
            ->map(function ($cat) {
                return [
                    'category_id'   => $cat->id,
                    'nama_kategori' => $cat->nama_kategori,
                    'total'         => $cat->complaints_count,
                ];
            })
            ->toArray();
    }

    /**
     * Get comprehensive facility statistics: counts per category, condition, and per lab breakdown.
     */
    public function getFacilityStats(?int $categoryId = null): array
    {
        $facilityQuery = Facility::query()->with(['lab', 'category']);

        if ($categoryId) {
            $facilityQuery->where('category_id', $categoryId);
        }

        $allFacilities = $facilityQuery->get();

        $totalUnits = $allFacilities->count();
        $totalBaik = $allFacilities->where('kondisi', 'baik')->count();
        $totalRusak = $allFacilities->where('kondisi', 'rusak')->count();

        // Breakdown per category
        $byCategory = Category::withCount([
            'facilities as total_units',
            'facilities as baik_units' => fn ($q) => $q->where('kondisi', 'baik'),
            'facilities as rusak_units' => fn ($q) => $q->where('kondisi', 'rusak'),
        ])->get();

        // Breakdown per lab
        $byLab = Lab::with([
            'facilities' => function ($q) use ($categoryId) {
                if ($categoryId) {
                    $q->where('category_id', $categoryId);
                }
                $q->with('category');
            },
        ])->get();

        return [
            'total_units' => $totalUnits,
            'total_baik'  => $totalBaik,
            'total_rusak' => $totalRusak,
            'by_category' => $byCategory,
            'by_lab'      => $byLab,
        ];
    }
}
