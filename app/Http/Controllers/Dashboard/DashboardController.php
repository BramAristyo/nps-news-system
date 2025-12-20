<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $summary = $this->dashboardService->summary();
        $recentNews = $this->dashboardService->getRecentNews(5);

        return inertia('Dashboard/Index', [
            'summary' => $summary,
            'recentNews' => $recentNews,
        ]);
    }
}
