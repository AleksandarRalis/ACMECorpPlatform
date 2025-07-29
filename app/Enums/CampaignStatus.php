<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * Get the display name for the status.
     */
    public function displayName(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::PAUSED => 'Paused',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    /**
     * Get all statuses that allow donations.
     */
    public static function donationAllowed(): array
    {
        return [self::ACTIVE];
    }

    /**
     * Check if the status allows donations.
     */
    public function allowsDonations(): bool
    {
        return in_array($this, self::donationAllowed());
    }

    /**
     * Get all available statuses.
     */
    public static function all(): array
    {
        return [
            self::PENDING,
            self::ACTIVE,
            self::PAUSED,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }
} 