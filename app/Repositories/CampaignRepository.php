<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\User;
use App\DTO\CampaignDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\CampaignRepositoryInterface;
use App\Enums\Pagination;
use App\Enums\CampaignStatus;

class CampaignRepository implements CampaignRepositoryInterface
{
    public function list(): LengthAwarePaginator
    {
        return Campaign::with('createdBy')
            ->where('status', CampaignStatus::ACTIVE->value)
            ->withCount('donations')
            ->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    public function listAll(): LengthAwarePaginator
    {
        return Campaign::with('createdBy')
            ->withCount('donations')
            ->orderBy('created_at', 'desc')
            ->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    public function listByUser(User $user): Collection
    {
        return Campaign::where('created_by', $user->id)
            ->withCount('donations')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(CampaignDTO $campaignDTO): Campaign
    {
        return Campaign::create($campaignDTO->toArray());
    }

    public function update(CampaignDTO $campaignDTO, Campaign $campaign): Campaign
    {
        $campaign->update($campaignDTO->toArray());
        
        return $campaign;
    }

    public function destroy(Campaign $campaign): bool
    {
        return $campaign->delete();
    }

    public function show(int $id): ?Campaign
    {
        return Campaign::findOrFail($id)->load('donations', 'createdBy');
    }

    public function activate(Campaign $campaign): Campaign
    {
        $campaign->update(['status' => CampaignStatus::ACTIVE->value]);
        return $campaign->fresh();
    }
}