<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatus;

class DonationDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'donation_id',
        'payment_method',
        'transaction_id',
        'payment_status',
        'gateway_response',
        'metadata',
        'processed_at',
        'failed_at',
        'failure_reason',
        'donor_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
            'failed_at' => 'datetime',
            'metadata' => 'array',
            'gateway_response' => 'array',
        ];
    }

    /**
     * Get the donation that this detail belongs to.
     */
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Get the user who processed the payment.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    /**
     * Check if the payment was successful.
     */
    public function isSuccessful(): bool
    {
        return $this->payment_status === PaymentStatus::COMPLETED->value;
    }

    /**
     * Check if the payment failed.
     */
    public function isFailed(): bool
    {
        return $this->payment_status === PaymentStatus::FAILED->value;
    }

    /**
     * Check if the payment is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === PaymentStatus::PENDING->value;
    }
} 