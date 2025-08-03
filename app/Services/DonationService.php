<?php

namespace App\Services;

use App\Models\User;
use App\DTO\DonationDTO;
use App\Models\Campaign;
use App\Models\Donation;
use App\Http\Resources\DonationResource;
use App\Interfaces\DonationRepositoryInterface;

class DonationService
{
    public function __construct(
        protected DonationRepositoryInterface $donationRepository
    ) {}

    /**
     * List all donations
     */
    public function list(array $filters): array
    {
        return DonationResource::collection($this->donationRepository->list($filters))
        ->response()
        ->getData(true);
    }

    /**
     * Get stats for donations management admin only
     */
    public function getStatsForDonationsManagement(): array
    {
        $stats = $this->donationRepository->getStats();
        
        // Handle case when no donations exist
        if (!$stats) {
            return [
                'total' => 0,
                'totalAmount' => '0.00',
                'averageAmount' => '0.00'
            ];
        }

        return [
            'total' => (int) $stats->total,
            'totalAmount' => number_format($stats->total_amount ?? 0, 2),
            'averageAmount' => number_format($stats->average_amount ?? 0, 2)
        ];  

    }

    /**
     * List donations by campaign
     */
    public function listDonationsByCampaign(Campaign $campaign): array
    {
        return DonationResource::collection($this->donationRepository
        ->listDonationsByCampaign($campaign))
        ->response()->getData(true);
    }

    /**
     * List donations by user
     */
    public function listDonationsByUser(User $user): array
    {
        return DonationResource::collection($this->donationRepository
        ->listDonationsByUser($user))
        ->response()->getData(true);
    }

    /**
     * Show a donation
     */
    public function show(Donation $donation): array
    {
        return (new DonationResource($this->donationRepository->loadRelationshipsForShow($donation)))->resolve();
    }

    /**
     * List donations by user
     */
    public function myDonations(User $user): array
    {
        return DonationResource::collection($this->donationRepository->myDonations($user))->response()->getData(true);
    }

    /**
     * Create a new donation
     */
    public function create(DonationDTO $donationDTO): Donation
    {
        return $this->donationRepository->create($donationDTO);
    }
}