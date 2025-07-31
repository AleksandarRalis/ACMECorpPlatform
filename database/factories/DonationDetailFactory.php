<?php

namespace Database\Factories;

use App\Models\DonationDetail;
use App\Models\Donation;
use App\Models\User;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationDetailFactory extends Factory
{
    protected $model = DonationDetail::class;

    public function definition(): array
    {
        return [
            'donation_id' => Donation::factory(),
            'donor_id' => User::factory(),
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'paypal', 'stripe']),
            'transaction_id' => $this->faker->unique()->uuid(),
            'payment_status' => PaymentStatus::PENDING->value,
            'gateway_response' => null,
            'metadata' => null,
            'processed_at' => null,
            'failed_at' => null,
            'failure_reason' => null,
        ];
    }

    /**
     * State for completed payments
     */
    public function completed(): self
    {
        return $this->state(fn () => [
            'payment_status' => PaymentStatus::COMPLETED->value,
            'processed_at' => now(),
            'gateway_response' => [
                'status' => 'success',
                'transaction_id' => $this->faker->uuid(),
                'amount' => $this->faker->randomFloat(2, 10, 1000),
            ],
        ]);
    }

    /**
     * State for failed payments
     */
    public function failed(): self
    {
        return $this->state(fn () => [
            'payment_status' => PaymentStatus::FAILED->value,
            'failed_at' => now(),
            'failure_reason' => $this->faker->randomElement([
                'Insufficient funds',
                'Card declined',
                'Invalid card number',
                'Network error',
                'Gateway timeout',
            ]),
            'gateway_response' => [
                'status' => 'failed',
                'error_code' => $this->faker->randomElement(['CARD_DECLINED', 'INSUFFICIENT_FUNDS', 'INVALID_CARD']),
                'error_message' => 'Payment failed',
            ],
        ]);
    }

    /**
     * State for cancelled payments
     */
    public function cancelled(): self
    {
        return $this->state(fn () => [
            'payment_status' => PaymentStatus::CANCELLED->value,
            'failed_at' => now(),
            'failure_reason' => 'Payment cancelled by user',
        ]);
    }

    /**
     * State for refunded payments
     */
    public function refunded(): self
    {
        return $this->state(fn () => [
            'payment_status' => PaymentStatus::REFUNDED->value,
            'processed_at' => now()->subDays(rand(1, 30)),
            'metadata' => [
                'refund_reason' => $this->faker->randomElement([
                    'Customer request',
                    'Duplicate charge',
                    'Service not provided',
                ]),
                'refund_amount' => $this->faker->randomFloat(2, 10, 1000),
            ],
        ]);
    }

    /**
     * State for credit card payments
     */
    public function creditCard(): self
    {
        return $this->state(fn () => [
            'payment_method' => 'credit_card',
            'metadata' => [
                'card_type' => $this->faker->randomElement(['visa', 'mastercard', 'amex']),
                'last_four' => $this->faker->numerify('####'),
            ],
        ]);
    }

    /**
     * State for PayPal payments
     */
    public function paypal(): self
    {
        return $this->state(fn () => [
            'payment_method' => 'paypal',
            'metadata' => [
                'paypal_email' => $this->faker->email(),
                'paypal_transaction_id' => $this->faker->uuid(),
            ],
        ]);
    }

    /**
     * State for bank transfer payments
     */
    public function bankTransfer(): self
    {
        return $this->state(fn () => [
            'payment_method' => 'bank_transfer',
            'metadata' => [
                'bank_name' => $this->faker->company(),
                'account_last_four' => $this->faker->numerify('####'),
                'reference' => $this->faker->bothify('REF-####-????'),
            ],
        ]);
    }

    /**
     * State for Stripe payments
     */
    public function stripe(): self
    {
        return $this->state(fn () => [
            'payment_method' => 'stripe',
            'transaction_id' => 'pi_' . $this->faker->regexify('[A-Za-z0-9]{24}'),
            'metadata' => [
                'stripe_payment_intent_id' => 'pi_' . $this->faker->regexify('[A-Za-z0-9]{24}'),
                'stripe_customer_id' => 'cus_' . $this->faker->regexify('[A-Za-z0-9]{24}'),
            ],
        ]);
    }
} 