<?php

namespace App\Http\Controllers\Api;

use App\DTO\DonationDTO;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Services\PaymentService;
use App\Services\DonationService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
     * Display a list of all donations.
     */
    public function index(Request $request): JsonResponse 
    {
        $filters = $request->only(['search', 'campaign', 'month']);
        return new JsonResponse($this->donationService->list($filters));
    }

    /**
     * Store a newly created donation.
     */
    public function store(InsertDonationRequest $request, Campaign $campaign): JsonResponse
    {
        $donorId = Auth::id();
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
    public function show(Donation $donation): JsonResponse 
    {
        return new JsonResponse($this->donationService->show($donation));
    }

    /**
     * Get donations made by the authenticated user.
     */
    public function myDonations(): JsonResponse 
    {
        return new JsonResponse($this->donationService->myDonations(Auth::user()));
    }
}
