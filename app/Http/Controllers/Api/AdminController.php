<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Get admin dashboard statistics
     */
    public function dashboard(): JsonResponse
    {
        try {
            // Get overall statistics
            $stats = [
                'activeCampaigns' => Campaign::where('status', 'active')
                    ->where('end_date', '>', now())
                    ->count(),
                'totalRaised' => Donation::whereHas('details', function ($query) {
                    $query->where('payment_status', 'completed');
                })->sum('amount'),
                'totalDonors' => Donation::whereHas('details', function ($query) {
                    $query->where('payment_status', 'completed');
                })->distinct('donor_id')->count(),
                'avgDonation' => round(Donation::whereHas('details', function ($query) {
                    $query->where('payment_status', 'completed');
                })->avg('amount'), 2),
            ];

            // Get recent campaigns (last 5)
            $recentCampaigns = Campaign::with(['donations' => function ($query) {
                $query->whereHas('details', function ($q) {
                    $q->where('payment_status', 'completed');
                });
            }])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($campaign) {
                $totalRaised = $campaign->donations->sum('amount');
                $progress = $campaign->goal_amount > 0 ? 
                    round(($totalRaised / $campaign->goal_amount) * 100, 1) : 0;
                
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'raised' => $totalRaised,
                    'progress' => $progress,
                    'created_at' => $campaign->created_at->format('Y-m-d'),
                    'goal_amount' => $campaign->goal_amount,
                    'status' => $campaign->status,
                    'days_left' => max(0, now()->diffInDays($campaign->end_date))
                ];
            });

            // Get recent donations (last 10)
            $recentDonations = Donation::with(['createdBy', 'campaign', 'details'])
                ->whereHas('details', function ($query) {
                    $query->where('payment_status', 'completed');
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($donation) {
                    return [
                        'id' => $donation->id,
                        'name' => $donation->createdBy->name,
                        'amount' => $donation->amount,
                        'campaign' => $donation->campaign->title,
                        'date' => $donation->created_at->format('Y-m-d'),
                        'message' => $donation->message,
                        'transaction_id' => $donation->details->transaction_id ?? null,
                    ];
                });

            // Get monthly statistics for the last 6 months
            $monthlyStats = $this->getMonthlyStats();

            // Get campaign performance data
            $campaignPerformance = $this->getCampaignPerformance();

            return response()->json([
                'stats' => $stats,
                'recentCampaigns' => $recentCampaigns,
                'recentDonations' => $recentDonations,
                'monthlyStats' => $monthlyStats,
                'campaignPerformance' => $campaignPerformance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly statistics for the last 6 months
     */
    private function getMonthlyStats(): array
    {
        $months = [];
        $donations = [];
        $campaigns = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthName = $date->format('M Y');
            
            $months[] = $monthName;
            
            // Get donations for this month
            $monthDonations = Donation::whereHas('details', function ($query) {
                $query->where('payment_status', 'completed');
            })
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum('amount');
            
            $donations[] = $monthDonations;
            
            // Get campaigns created this month
            $monthCampaigns = Campaign::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $campaigns[] = $monthCampaigns;
        }

        return [
            'months' => $months,
            'donations' => $donations,
            'campaigns' => $campaigns
        ];
    }

    /**
     * Get campaign performance data
     */
    private function getCampaignPerformance(): array
    {
        return Campaign::with(['donations' => function ($query) {
            $query->whereHas('details', function ($q) {
                $q->where('payment_status', 'completed');
            });
        }])
        ->get()
        ->map(function ($campaign) {
            $totalRaised = $campaign->donations->sum('amount');
            $donorCount = $campaign->donations->count();
            $progress = $campaign->goal_amount > 0 ? 
                round(($totalRaised / $campaign->goal_amount) * 100, 1) : 0;
            
            return [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'goal_amount' => $campaign->goal_amount,
                'total_raised' => $totalRaised,
                'progress' => $progress,
                'donor_count' => $donorCount,
                'avg_donation' => $donorCount > 0 ? round($totalRaised / $donorCount, 2) : 0,
                'status' => $campaign->status,
                'days_left' => max(0, now()->diffInDays($campaign->end_date)),
                'created_at' => $campaign->created_at->format('Y-m-d')
            ];
        })
        ->sortByDesc('total_raised')
        ->values()
        ->toArray();
    }

    /**
     * Get detailed campaign statistics
     */
    public function campaignStats(Campaign $campaign): JsonResponse
    {
        try {
            $donations = $campaign->donations()
                ->whereHas('details', function ($query) {
                    $query->where('payment_status', 'completed');
                })
                ->with(['createdBy', 'details'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalRaised = $donations->sum('amount');
            $donorCount = $donations->count();
            $progress = $campaign->goal_amount > 0 ? 
                round(($totalRaised / $campaign->goal_amount) * 100, 1) : 0;

            // Get donation distribution by amount ranges
            $donationRanges = [
                '0-25' => $donations->where('amount', '<=', 25)->count(),
                '26-50' => $donations->whereBetween('amount', [26, 50])->count(),
                '51-100' => $donations->whereBetween('amount', [51, 100])->count(),
                '101-250' => $donations->whereBetween('amount', [101, 250])->count(),
                '251+' => $donations->where('amount', '>', 250)->count(),
            ];

            return response()->json([
                'campaign' => [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'goal_amount' => $campaign->goal_amount,
                    'total_raised' => $totalRaised,
                    'progress' => $progress,
                    'donor_count' => $donorCount,
                    'avg_donation' => $donorCount > 0 ? round($totalRaised / $donorCount, 2) : 0,
                    'status' => $campaign->status,
                    'days_left' => max(0, now()->diffInDays($campaign->end_date)),
                    'created_at' => $campaign->created_at->format('Y-m-d')
                ],
                'donations' => $donations->map(function ($donation) {
                    return [
                        'id' => $donation->id,
                        'donor_name' => $donation->donor_name,
                        'amount' => $donation->amount,
                        'message' => $donation->message,
                        'anonymous' => $donation->anonymous,
                        'transaction_id' => $donation->details->transaction_id ?? null,
                        'created_at' => $donation->created_at->format('Y-m-d H:i:s')
                    ];
                }),
                'donationRanges' => $donationRanges
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load campaign statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user management data
     */
    public function users(): JsonResponse
    {
        try {
            $users = User::withCount(['campaigns', 'donations'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json($users);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 