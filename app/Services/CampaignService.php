<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\CampaignRepositoryInterface;
use App\DTO\CampaignDTO;
use App\Http\Resources\CampaignResource;

class CampaignService
{
    public function __construct(
        protected CampaignRepositoryInterface $campaignRepository
    ) {}

    /**
     * Get all campaigns with optional filters.
     */
    public function list(array $filters = []): array
    {
        return CampaignResource::collection($this->campaignRepository->list())->response()->getData(true);
    }

    /**
     * Get campaigns created by a specific user.
     */
    public function listByUser(User $user): Collection
    {
        return $this->campaignRepository->listByUser($user);
    }

    /**
     * Create a new campaign.
     */
    public function create(CampaignDTO $campaignDTO): Campaign
    {
        return $this->campaignRepository->create($campaignDTO);
    }

    /**
     * Update an existing campaign.
     */
    public function update(Campaign $campaign, array $data): bool
    {
        return $this->campaignRepository->update($campaign, $data);
    }

    /**
     * Delete a campaign.
     */
    public function destroy(Campaign $campaign): bool
    {
        return $this->campaignRepository->destroy($campaign);
    }

    /**
     * Get campaign by ID.
     */
    public function show(int $id): ?CampaignResource
    {
        return CampaignResource::make($this->campaignRepository->show($id));
    }
} 