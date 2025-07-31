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

    public function list(array $filters): array
    {
        return DonationResource::collection($this->donationRepository->list($filters))
        ->response()
        ->getData(true);
    }

    public function listDonationsByCampaign(Campaign $campaign): array
    {
        return DonationResource::collection($this->donationRepository
        ->listDonationsByCampaign($campaign))
        ->response()->getData(true);
    }

    public function listDonationsByUser(User $user): array
    {
        return DonationResource::collection($this->donationRepository
        ->listDonationsByUser($user))
        ->response()->getData(true);
    }
    
    public function show(Donation $donation): array
    {
        return (new DonationResource($this->donationRepository->loadRelationshipsForShow($donation)))->resolve();
    }

    public function myDonations(User $user): array
    {
        return DonationResource::collection($this->donationRepository->myDonations($user))->response()->getData(true);
    }

    public function create(DonationDTO $donationDTO): Donation
    {
        return $this->donationRepository->create($donationDTO);
    }
}