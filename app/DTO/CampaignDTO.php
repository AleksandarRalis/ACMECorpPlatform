<?php

namespace App\DTO;

use App\Interfaces\DTO;
use App\Models\Campaign;
use App\Enums\CampaignStatus;
use App\Http\Requests\UpsertCampaignRequest;

class CampaignDTO implements DTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $category,
        public float $goalAmount,
        public float $currentAmount,
        public ?string $imageUrl,
        public string $startDate,
        public string $endDate,
        public string $status,
        public int $createdBy,
    ) {}

    public static function fromRequest(UpsertCampaignRequest $request): CampaignDTO
    {
        return new self(
            title: $request->title,
            description: $request->description,
            category: $request->category,
            goalAmount: $request->goal_amount,
            currentAmount: 0, // Always start with 0 for a new campaign
            imageUrl: $request->image_url,
            startDate: $request->start_date,
            endDate: $request->end_date,
            status: CampaignStatus::PENDING->value, // Default status for new campaign
            createdBy: $request->user()->id,
        );
    }


public static function fromRequestForUpdate(UpsertCampaignRequest $request, Campaign $campaign): CampaignDTO
{
    return new self(
        title: $request->title,
        description: $request->description,
        category: $request->category,
        goalAmount: $request->goal_amount,
        currentAmount: $campaign->current_amount, 
        imageUrl: $request->image_url,
        startDate: $request->start_date,
        endDate: $request->end_date,
        status: $request->status ?? CampaignStatus::PENDING->value,
        createdBy: $campaign->created_by,
    );
}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'goal_amount' => $this->goalAmount,
            'current_amount' => $this->currentAmount,
            'image_url' => $this->imageUrl,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'status' => $this->status,
            'created_by' => $this->createdBy,
        ];
    }
}