<?php

use App\Models\Donation;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Role;
use App\Enums\CampaignStatus;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Create roles before each test to ensure they exist
    Role::factory()->admin()->create();
    Role::factory()->employee()->create();
});

test('donation can be created with required fields', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);

    expect($donation)
        ->amount->toBeGreaterThan(0)
        ->campaign_id->toBe($campaign->id)
        ->donor_id->toBe($user->id);
});

test('donation can be created with different amounts', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    
    $smallDonation = Donation::factory()->small()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $mediumDonation = Donation::factory()->medium()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $largeDonation = Donation::factory()->large()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);

    expect($smallDonation->amount)->toBeLessThan(50);
    expect($mediumDonation->amount)->toBeGreaterThanOrEqual(50);
    expect($mediumDonation->amount)->toBeLessThanOrEqual(500);
    expect($largeDonation->amount)->toBeGreaterThan(1000);
});

test('donation can be created with or without message', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    
    $donationWithMessage = Donation::factory()->withMessage()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);
    $donationWithoutMessage = Donation::factory()->withoutMessage()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);

    expect($donationWithMessage->message)->not->toBeNull();
    expect($donationWithoutMessage->message)->toBeNull();
});

test('donation has relationship with campaign', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);

    expect($donation->campaign)->toBeInstanceOf(Campaign::class);
    expect($donation->campaign->id)->toBe($campaign->id);
});

test('donation has relationship with donor', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);

    expect($donation->createdBy)->toBeInstanceOf(User::class);
    expect($donation->createdBy->id)->toBe($user->id);
});

test('donation can have donation details', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);

    expect($donation->details)->toBeNull();
});

test('donation returns correct donor name', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
    ]);

    // Load the relationship
    $donation->load('createdBy');

    expect($donation->donor_name)->toBe($user->name);
});


test('donation can be created with specific amount', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
        'amount' => 500.00,
    ]);

    expect($donation->amount)->toBe('500.00');
});

test('donation can be created with specific message', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $donation = Donation::factory()->create([
        'campaign_id' => $campaign->id,
        'donor_id' => $user->id,
        'message' => 'This is a test donation message',
    ]);

    expect($donation->message)->toBe('This is a test donation message');
});

test('campaign can have multiple donations', function () {
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

    expect($campaign->donations)->toHaveCount(2);
    expect($campaign->donations->pluck('id')->toArray())->toContain($donation1->id);
    expect($campaign->donations->pluck('id')->toArray())->toContain($donation2->id);
});

test('user can have multiple donations', function () {
    $user = User::factory()->admin()->create();
    $campaign1 = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $campaign2 = Campaign::factory()->active()->create(['created_by' => $user->id]);
    
    $donation1 = Donation::factory()->create([
        'campaign_id' => $campaign1->id,
        'donor_id' => $user->id,
    ]);
    $donation2 = Donation::factory()->create([
        'campaign_id' => $campaign2->id,
        'donor_id' => $user->id,
    ]);

    expect($user->donations)->toHaveCount(2);
    expect($user->donations->pluck('id')->toArray())->toContain($donation1->id);
    expect($user->donations->pluck('id')->toArray())->toContain($donation2->id);
}); 