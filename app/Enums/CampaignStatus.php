<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case INACTIVE = 'inactive';
    /**
     * Get the display name for the status.
     */
    public function displayName(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::INACTIVE => 'Inactive',
        };
    }
} 