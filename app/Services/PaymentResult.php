<?php

namespace App\Services;

class PaymentResult
{
    public function __construct(
        public readonly string $payment_method = '',
        public readonly ?string $transaction_id = null,
        public readonly ?string $payment_status = null,
        public readonly ?string $gateway_response = null,
        public readonly ?string $metadata = null,
        public readonly ?string $processed_at = null,
        public readonly ?string $failed_at = null,
        public readonly ?string $failed_reason = null
    ) {}

    public function isSuccessful(): bool
    {
        return $this->payment_status;
    }

    public function getErrorMessage(): ?string
    {
        return $this->failed_reason;
    }
}
