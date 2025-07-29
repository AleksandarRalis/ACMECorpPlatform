<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Display a listing of donations for a campaign.
     */
    public function index(Campaign $campaign): JsonResponse
    {
        $donations = $campaign->donations()
            ->with(['donor', 'details'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'donations' => $donations,
        ]);
    }

    /**
     * Store a newly created donation.
     */
    public function store(Request $request, Campaign $campaign): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'anonymous' => 'boolean',
            'message' => 'nullable|string|max:500',
        ]);

        // Check if campaign is active
        if (!$campaign->isActive()) {
            return response()->json([
                'message' => 'This campaign is not accepting donations',
            ], 400);
        }

        // Check if campaign has ended
        if ($campaign->end_date->isPast()) {
            return response()->json([
                'message' => 'This campaign has ended',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create donation
            $donation = Donation::create([
                'campaign_id' => $campaign->id,
                'donor_id' => Auth::id(),
                'amount' => $request->amount,
                'anonymous' => $request->boolean('anonymous', false),
                'message' => $request->message,
            ]);

            // Create donation details (payment processing)
            $donationDetail = DonationDetail::create([
                'donation_id' => $donation->id,
                'payment_method' => 'dummy', // For MVP
                'transaction_id' => 'TXN_' . uniqid(),
                'payment_status' => 'completed', // Simulate successful payment
                'gateway_response' => json_encode(['status' => 'success']),
                'metadata' => [
                    'processor' => 'dummy',
                    'simulated' => true,
                ],
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            // Update campaign current amount
            $campaign->increment('current_amount', $request->amount);

            DB::commit();

            return response()->json([
                'message' => 'Donation successful',
                'donation' => $donation->load(['details']),
                'transaction_id' => $donationDetail->transaction_id,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Donation failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the specified donation.
     */
    public function show(Donation $donation): JsonResponse
    {
        // Check if user can view this donation
        if ($donation->donor_id !== Auth::id() && 
            $donation->campaign->created_by !== Auth::id() && 
            !Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized to view this donation',
            ], 403);
        }

        return response()->json([
            'donation' => $donation->load(['donor', 'campaign', 'details']),
        ]);
    }

    /**
     * Get donations made by the authenticated user.
     */
    public function myDonations(): JsonResponse
    {
        $donations = Auth::user()->donations()
            ->with(['campaign', 'details'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'donations' => $donations,
        ]);
    }

    /**
     * Get donation statistics for a campaign.
     */
    public function statistics(Campaign $campaign): JsonResponse
    {
        $stats = [
            'total_donations' => $campaign->donations()->count(),
            'total_amount' => $campaign->donations()->sum('amount'),
            'average_donation' => $campaign->donations()->avg('amount'),
            'anonymous_donations' => $campaign->donations()->where('anonymous', true)->count(),
            'unique_donors' => $campaign->donations()->distinct('donor_id')->count(),
        ];

        return response()->json([
            'statistics' => $stats,
        ]);
    }
} 