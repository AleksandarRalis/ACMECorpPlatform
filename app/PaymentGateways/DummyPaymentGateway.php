<?php

namespace App\PaymentGateways;

use App\DTO\DonationDTO;
use Illuminate\Support\Str;
use App\Services\PaymentResult;
use App\Interfaces\PaymentGatewayInterface;

class DummyPaymentGateway implements PaymentGatewayInterface
{
    public function authorizePayment() {}

    public function processPayment(DonationDTO $donationDTO): PaymentResult
    {
        // Example: Call external API (e.g., PayPal)
        // Dummy response - always successful
        $apiResponse = [
            'status' => 'Completed',
            'id' => Str::random(9),
            'amount' => $donationDTO->amount,
        ];
        
        return new PaymentResult(
            payment_method: 'PayPal',
            transaction_id: $apiResponse['id'],
            payment_status: 'completed',
            gateway_response: '{"auth_code": "AUTH987654", "avs_result": "Y", "cvv_result": "M", "gateway_message": "Payment authorized and captured", "gateway_reference": "GWREF12345"}',
            metadata: '{"customer_id": "CUST_00123", "order_id": "ORD_56789","ip_address": "192.168.1.25","platform": "web","notes": "Gift purchase, do not include invoice"}',
            processed_at: '2025-07-07',
            failed_at: null,
            failed_reason: null,
        );
    }

    public function refundPayment() {}
}
