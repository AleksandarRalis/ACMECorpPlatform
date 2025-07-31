<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Carbon;
use App\Interfaces\AdminDashboardRepositoryInterface;

class AdminDashboardRepository implements AdminDashboardRepositoryInterface
{
    public function getDashboardData(): array
    {
    
        return [
            'stats' => $this->getOverallStatistics(),
            'recentCampaigns' => $this->getRecentCampaigns(),
            'recentDonations' => $this->getRecentDonations(),
            'monthlyStats' => $this->getMonthlyStats(),
            'campaignPerformance' => $this->getCampaignPerformance()
        ];
    }

    public function getOverallStatistics(): array
    {
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
        
        return $stats;
    }

    public function getRecentCampaigns(): array
    {
        return Campaign::with(['donations' => function ($query) {
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
        })->toArray();

    }

    public function getRecentDonations(): array
    {
        return Donation::with(['createdBy', 'campaign', 'details'])
                ->whereHas('details', function ($query) {
                    $query->where('payment_status', 'completed');
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
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
                })->toArray();
    }

    public function getMonthlyStats(): array
    {
        $months = [];
        $donations = [];
        $campaigns = [];
        $now = Carbon::now();
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonthsNoOverflow($i);
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

    public function getCampaignPerformance(): array
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
                'days_left' => round(max(0, now()->diffInDays($campaign->end_date))),
                'created_at' => $campaign->created_at->format('Y-m-d')
            ];
        })
        ->sortByDesc('total_raised')
        ->values()
        ->toArray();
    }
}