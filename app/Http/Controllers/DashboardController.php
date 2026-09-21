<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        public DashboardService $dashboardService,
    ) {}
    public function index()
    {
        $dashboardData = $this->dashboardService->getData();
        return view('dashboard.index', compact('dashboardData'));
    }
}
