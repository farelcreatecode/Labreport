<?php

namespace App\Http\Controllers\Pelapor;

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
     * Display pelapor dashboard.
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        // Count per status for current user
        $counts = $this->statisticsService->getStatusCounts($userId);
        $categoryCounts = $this->statisticsService->getCategoryCounts($userId);

        // 5 most recent complaints with eager loading
        $recentComplaints = Complaint::with(['lab', 'category', 'technician'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('pelapor.dashboard', compact('counts', 'categoryCounts', 'recentComplaints'));
    }
}
