<?php

namespace App\Http\Controllers\Api;

use App\DTO\DonationDTO;
use App\Models\Campaign;
use App\Models\Donation;
use App\DTO\DonationDetailDTO;
use App\Services\PaymentService;
use App\Services\DonationService;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\DonationDetailService;
use App\Http\Requests\InsertDonationRequest;

class DonationsController extends Controller
{
    public function __construct(
        protected DonationService $donationService,
        protected PaymentService $paymentService,
        protected DonationDetailService $donationDetailService,
        protected EmailService $emailService,
    ) {}
    /**
     * Display a listing of donations for a campaign.
     */
    public function index(Campaign $campaign): JsonResponse {}

    /**
     * Store a newly created donation.
     */
    public function store(InsertDonationRequest $request, Campaign $campaign): JsonResponse
    {
        $donorId = auth()->id();
        $donationDTO = DonationDTO::fromRequest($request, $campaign->id, $donorId);

        // Process payment
        $paymentResult = $this->paymentService->processPayment($donationDTO, $donorId);

        if (!$paymentResult->isSuccessful()) {
            return new JsonResponse(['error' => $paymentResult->getErrorMessage()], 422);
        }
        $donation = $this->paymentService->processDonation($donationDTO, $paymentResult, $donorId);

        // Send confirmation email
        $this->emailService->sendDonationConfirmationAsync($donation);

        return new JsonResponse($donation);
    }

    /**
     * Display the specified donation.
     */
    public function show(Donation $donation): JsonResponse {}

    /**
     * Get donations made by the authenticated user.
     */
    public function myDonations(): JsonResponse {}

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
