<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'goal_amount' => (float) $this->goal_amount,
            'current_amount' => (float) $this->current_amount,
            'image_url' => $this->image_url,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'created_by' => $this->whenLoaded('createdBy', function () {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                    'email' => $this->createdBy->email,
                ];
            }),

            'donations' => $this->whenLoaded('donations', function () {
                return $this->donations->map(function ($donation) {
                    return [
                        'name' => $donation->createdBy->name,
                        'message' => $donation->message,
                        'amount' => $donation->amount,
                    ];
                });
            }),
            
            'progress_percentage' => min(round(($this->current_amount / $this->goal_amount) * 100, 2), 100),
            'days_left' => (int) max(0, now()->diffInDays(\Carbon\Carbon::parse($this->end_date))),
            'donations_count' => $this->donations_count ?? $this->donations()->count(),

        ];
    }
} 