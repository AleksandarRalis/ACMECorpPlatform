<?php

namespace App\Repositories;

use App\Models\User;
use App\DTO\DonationDTO;
use App\Models\Campaign;
use App\Models\Donation;
use App\Interfaces\DonationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DonationRepository implements DonationRepositoryInterface
{
    public function list(array $filters): LengthAwarePaginator
    {   
        $query = Donation::with('campaign', 'createdBy', 'details');

        // Search filter
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->whereHas('createdBy', function($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', "%{$searchTerm}%");
            });
        }

        // Campaign filter
        if (!empty($filters['campaign'])) {
            $searchTerm = $filters['campaign'];
            $query->whereHas('campaign', function($subQuery) use ($searchTerm) {
                $subQuery->where('title', 'like', "%{$searchTerm}%");
            });
        }

        // Month filter
        if (!empty($filters['month'])) {
            $query->whereMonth('created_at', $filters['month']);
        }

        return $query->paginate(10);
    }

    public function listDonationsByCampaign(Campaign $campaign): LengthAwarePaginator
    {
        return Donation::with('campaign', 'createdBy', 'details')->where('campaign_id', $campaign->id)->paginate(10);
    }

    public function listDonationsByUser(User $user): LengthAwarePaginator
    {
        return Donation::with('campaign', 'createdBy', 'details')->where('donor_id', $user->id)->paginate(10);
    }

    public function loadRelationshipsForShow(Donation $donation): Donation
    {
        return $donation->load(['campaign', 'createdBy', 'details']);
    }

    public function myDonations(User $user): LengthAwarePaginator
    {
        return Donation::with('campaign', 'createdBy', 'details')->where('donor_id', $user->id)->paginate(10);
    }

    public function create(DonationDTO $donationDTO): Donation
    {
        return Donation::create($donationDTO->toArray());
    }
}