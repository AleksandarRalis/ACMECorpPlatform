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
    public function list(array $filters = []): LengthAwarePaginator
    {
        /**
         * List the campaigns.
         */
        $query = Campaign::with('createdBy')
            ->where('status', CampaignStatus::ACTIVE->value)
            ->withCount('donations');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    /**
     * List all campaigns.
     */
    public function listAll(): LengthAwarePaginator
    {
        return Campaign::with('createdBy')
            ->withCount('donations')
            ->orderBy('created_at', 'desc')
            ->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    /**
     * List campaigns by user.
     */
    public function listByUser(User $user, array $filters = []): Collection
    {
        $query = Campaign::where('created_by', $user->id)
            ->withCount('donations');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a campaign.
     */
    public function create(CampaignDTO $campaignDTO): Campaign
    {
        return Campaign::create($campaignDTO->toArray());
    }

    /**
     * Update a campaign.
     */
    public function update(CampaignDTO $campaignDTO, Campaign $campaign): Campaign
    {
        $campaign->update($campaignDTO->toArray());
        
        return $campaign;
    }

    /**
     * Destroy a campaign.
     */
    public function destroy(Campaign $campaign): bool
    {
        return $campaign->delete();
    }

    /**
     * Show a campaign.
     */
    public function show(int $id): ?Campaign
    {
        return Campaign::findOrFail($id)->load('donations', 'createdBy');
    }

    /**
     * Activate a campaign.
     */
    public function activate(Campaign $campaign): Campaign
    {
        $campaign->update(['status' => CampaignStatus::ACTIVE->value]);
        return $campaign->fresh();
    }
}