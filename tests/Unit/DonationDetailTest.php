<?php

use App\Models\DonationDetail;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Role;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Create roles before each test to ensure they exist
    Role::factory()->admin()->create();
    Role::factory()->employee()->create();
});

test('donation detail can be created with required fields', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    
    $donationDetail = DonationDetail::factory()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donationDetail)
        ->payment_method->not->toBeEmpty()
        ->transaction_id->not->toBeEmpty()
        ->payment_status->toBe(PaymentStatus::PENDING->value)
        ->donation_id->toBe($donation->id)
        ->donor_id->toBe($user->id);
});

test('donation detail can be created with different payment statuses', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    
    $completedDetail = DonationDetail::factory()->completed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $failedDetail = DonationDetail::factory()->failed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $cancelledDetail = DonationDetail::factory()->cancelled()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $refundedDetail = DonationDetail::factory()->refunded()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($completedDetail->payment_status)->toBe(PaymentStatus::COMPLETED->value);
    expect($failedDetail->payment_status)->toBe(PaymentStatus::FAILED->value);
    expect($cancelledDetail->payment_status)->toBe(PaymentStatus::CANCELLED->value);
    expect($refundedDetail->payment_status)->toBe(PaymentStatus::REFUNDED->value);
});

test('donation detail can be created with different payment methods', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    
    $creditCardDetail = DonationDetail::factory()->creditCard()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $paypalDetail = DonationDetail::factory()->paypal()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $bankTransferDetail = DonationDetail::factory()->bankTransfer()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $stripeDetail = DonationDetail::factory()->stripe()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($creditCardDetail->payment_method)->toBe('credit_card');
    expect($paypalDetail->payment_method)->toBe('paypal');
    expect($bankTransferDetail->payment_method)->toBe('bank_transfer');
    expect($stripeDetail->payment_method)->toBe('stripe');
});

test('donation detail has relationship with donation', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donationDetail->donation)->toBeInstanceOf(Donation::class);
    expect($donationDetail->donation->id)->toBe($donation->id);
});

test('donation detail has relationship with processor', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    // Load the relationship
    $donationDetail->load('processor');

    expect($donationDetail->processor)->toBeInstanceOf(User::class);
    expect($donationDetail->processor->id)->toBe($user->id);
});

test('donation detail can check if payment was successful', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    
    $completedDetail = DonationDetail::factory()->completed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $pendingDetail = DonationDetail::factory()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($completedDetail->isSuccessful())->toBeTrue();
    expect($pendingDetail->isSuccessful())->toBeFalse();
});

test('donation detail can check if payment failed', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    
    $failedDetail = DonationDetail::factory()->failed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $pendingDetail = DonationDetail::factory()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($failedDetail->isFailed())->toBeTrue();
    expect($pendingDetail->isFailed())->toBeFalse();
});

test('donation detail can check if payment is pending', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    
    $pendingDetail = DonationDetail::factory()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);
    $completedDetail = DonationDetail::factory()->completed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($pendingDetail->isPending())->toBeTrue();
    expect($completedDetail->isPending())->toBeFalse();
});

test('donation detail has proper datetime casting', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->completed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donationDetail->processed_at)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('donation detail has proper array casting for metadata', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->creditCard()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donationDetail->metadata)->toBeArray();
    expect($donationDetail->metadata)->toHaveKey('card_type');
    expect($donationDetail->metadata)->toHaveKey('last_four');
});

test('donation detail can be created with gateway response', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->completed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donationDetail->gateway_response)->toBeArray();
    expect($donationDetail->gateway_response)->toHaveKey('status');
    expect($donationDetail->gateway_response['status'])->toBe('success');
});

test('donation detail can be created with failure reason', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->failed()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donationDetail->failure_reason)->not->toBeNull();
    expect($donationDetail->failed_at)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('donation detail can be created with refund metadata', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->refunded()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donationDetail->metadata)->toHaveKey('refund_reason');
    expect($donationDetail->metadata)->toHaveKey('refund_amount');
    expect($donationDetail->processed_at)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('donation detail has unique transaction id', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation1 = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donation2 = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    
    $detail1 = DonationDetail::factory()->create([
        'donation_id' => $donation1->id,
        'donor_id' => $user->id,
    ]);
    $detail2 = DonationDetail::factory()->create([
        'donation_id' => $donation2->id,
        'donor_id' => $user->id,
    ]);

    expect($detail1->transaction_id)->not->toBe($detail2->transaction_id);
});

test('donation can have donation details relationship', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationDetail = DonationDetail::factory()->create([
        'donation_id' => $donation->id,
        'donor_id' => $user->id,
    ]);

    expect($donation->details)->toBeInstanceOf(DonationDetail::class);
    expect($donation->details->id)->toBe($donationDetail->id);
}); 