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

    /**
     * Get overall stats for the donations.
     */
    public function getStats(): ?Donation
    {
        $stats = Donation::selectRaw('
            COUNT(*) as total,
            SUM(amount) as total_amount,
            AVG(amount) as average_amount
        ')->first();

        return $stats;
    }

    /**
     * List donations by campaign.
     */
    public function listDonationsByCampaign(Campaign $campaign): LengthAwarePaginator
    {
        return Donation::with('campaign', 'createdBy', 'details')->where('campaign_id', $campaign->id)->paginate(10);
    }

    /**
     * List donations by user.
     */
    public function listDonationsByUser(User $user): LengthAwarePaginator
    {
        return Donation::with('campaign', 'createdBy', 'details')->where('donor_id', $user->id)->paginate(10);
    }

    /**
     * Load relationships for show.
     */
    public function loadRelationshipsForShow(Donation $donation): Donation
    {
        return $donation->load(['campaign', 'createdBy', 'details']);
    }

    /**
     * List donations by user.
     */
    public function myDonations(User $user): LengthAwarePaginator
    {
        return Donation::with('campaign', 'createdBy', 'details')->where('donor_id', $user->id)->paginate(10);
    }

    /**
     * Create a donation.
     */
    public function create(DonationDTO $donationDTO): Donation
    {
        return Donation::create($donationDTO->toArray());
    }
}