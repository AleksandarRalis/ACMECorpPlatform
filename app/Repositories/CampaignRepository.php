<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\User;
use App\DTO\CampaignDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\CampaignRepositoryInterface;
use App\Enums\Pagination;

class CampaignRepository implements CampaignRepositoryInterface
{
    public function list(): LengthAwarePaginator
    {
        return Campaign::with('createdBy')
            ->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    public function listByUser(User $user): Collection
    {
        return Campaign::where('created_by', $user->id)
            ->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    public function create(CampaignDTO $campaignDTO): Campaign
    {
        return Campaign::create($campaignDTO->toArray());
    }

    public function update(Campaign $campaign, array $data): bool
    {
        return $campaign->update($data);
    }

    public function destroy(Campaign $campaign): bool
    {
        return $campaign->delete();
    }

    public function show(int $id): ?Campaign
    {
        return Campaign::findOrFail($id)->load('donations', 'createdBy');
    }
}