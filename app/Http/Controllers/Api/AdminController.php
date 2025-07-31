<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;

class AdminController extends Controller
{
    public function __construct(
        protected AdminDashboardService $adminDashboardService
    ) {}

    /**
     * Get admin dashboard statistics
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(
            $this->adminDashboardService->getDashboardData()
        );
    }
} 