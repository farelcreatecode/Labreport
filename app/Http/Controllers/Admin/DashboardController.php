<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected StatisticsService $statisticsService
    ) {}

    /**
     * Display admin and technician dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Overall status counts
        $counts = $this->statisticsService->getStatusCounts();

        // Category counts (Software, Hardware, Jaringan)
        $categoryCounts = $this->statisticsService->getCategoryCounts();

        // Doughnut chart payload (Category distribution)
        $chartData = $this->statisticsService->getCategoryChartData();

        // 5 most recent complaints
        $recentComplaints = Complaint::with(['user', 'lab', 'category', 'technician'])
            ->latest()
            ->take(5)
            ->get();

        // If technician, also get counts assigned to this technician
        $myAssignedCount = null;
        if ($user->isTechnician()) {
            $myAssignedCount = Complaint::where('technician_id', $user->id)
                ->whereIn('status', ['pending', 'diproses'])
                ->count();
        }

        return view('admin.dashboard', compact('counts', 'categoryCounts', 'chartData', 'recentComplaints', 'myAssignedCount'));
    }
}
