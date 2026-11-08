<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatisticController extends Controller
{
    public function __construct(
        protected StatisticsService $statisticsService
    ) {}

    /**
     * Display detailed reporting statistics.
     */
    public function index(Request $request): View
    {
        $year = $request->filled('year') ? (int) $request->year : now()->year;

        $monthlyData = $this->statisticsService->getMonthlyComplaintsCount($year);
        $perLabData = $this->statisticsService->getComplaintsPerLab();
        $perCategoryData = $this->statisticsService->getComplaintsPerCategory();
        $statusCounts = $this->statisticsService->getStatusCounts();
        $chartData = $this->statisticsService->getStatusChartData();

        return view('admin.statistics.index', compact(
            'year',
            'monthlyData',
            'perLabData',
            'perCategoryData',
            'statusCounts',
            'chartData'
        ));
    }
}
