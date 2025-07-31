<?php

namespace App\DTO;

use App\Http\Requests\InsertDonationRequest;

class DonationDTO
{
    public function __construct(
        public int $campaign_id,
        public int $donor_id,
        public float $amount,
        public ?string $message
    ) {}

    public static function fromRequest(InsertDonationRequest $request, int $campaignId, int $donorId): DonationDTO
    {
        return new self(
            campaign_id: $campaignId,
            donor_id: $donorId,
            amount: $request->amount,
            message: $request->message,
        );
    }
    
    public function toArray(): array
    {
        return [
            'campaign_id' => $this->campaign_id,
            'donor_id' => $this->donor_id,
            'amount' => $this->amount,
            'message' => $this->message,
        ];
    }
}