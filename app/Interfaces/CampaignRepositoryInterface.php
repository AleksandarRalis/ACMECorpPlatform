<?php

namespace App\Interfaces;

use App\Models\Campaign;
use App\Models\User;
use App\DTO\CampaignDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CampaignRepositoryInterface
{
    public function list(): LengthAwarePaginator;

    public function listAll(): LengthAwarePaginator;
    
    public function show(int $id): ?Campaign;

    public function listByUser(User $user): Collection;

    public function create(CampaignDTO $campaignDTO): Campaign;

    public function update(CampaignDTO $campaignDTO, Campaign $campaign): Campaign;

    public function destroy(Campaign $campaign): bool;

    public function activate(Campaign $campaign): Campaign;
}