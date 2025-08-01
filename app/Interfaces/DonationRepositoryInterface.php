<?php

namespace App\Interfaces;

use App\Models\User;
use App\DTO\DonationDTO;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Pagination\LengthAwarePaginator;

interface DonationRepositoryInterface
{
    public function list(array $filters): LengthAwarePaginator;
    
    public function getStats(): array;

    public function listDonationsByCampaign(Campaign $campaign): LengthAwarePaginator;

    public function listDonationsByUser(User $user): LengthAwarePaginator;
    
    public function loadRelationshipsForShow(Donation $donation): Donation;

    public function myDonations(User $user): LengthAwarePaginator;
    
    public function create(DonationDTO $donationDTO): Donation;
}