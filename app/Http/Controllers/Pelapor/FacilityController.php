<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function __construct(
        protected StatisticsService $statisticsService
    ) {}

    /**
     * Display facilities overview with category breakdown and condition per lab.
     */
    public function index(Request $request): View
    {
        $selectedCategoryId = $request->filled('category_id') ? (int) $request->category_id : null;

        $categories = Category::orderBy('nama_kategori')->get();
        $facilityStats = $this->statisticsService->getFacilityStats($selectedCategoryId);

        return view('pelapor.facilities.index', compact('categories', 'facilityStats', 'selectedCategoryId'));
    }
}
