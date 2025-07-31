<?php

use App\Models\Campaign;
use App\Models\User;
use App\Models\Role;
use App\Enums\CampaignStatus;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Create roles before each test to ensure they exist
    Role::factory()->admin()->create();
    Role::factory()->employee()->create();
});

test('campaign can be created with required fields', function () {
    $user = User::factory()->admin()->create();
    
    $campaign = Campaign::factory()->create([
        'created_by' => $user->id,
    ]);

    expect($campaign)
        ->title->not->toBeEmpty()
        ->description->not->toBeEmpty()
        ->category->not->toBeEmpty()
        ->goal_amount->toBeGreaterThan(0)
        ->current_amount->toBe('0.00')
        ->status->toBe(CampaignStatus::PENDING->value)
        ->created_by->toBe($user->id);
});

test('campaign can be created with different statuses', function () {
    $user = User::factory()->admin()->create();
    
    $activeCampaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $completedCampaign = Campaign::factory()->completed()->create(['created_by' => $user->id]);
    $cancelledCampaign = Campaign::factory()->cancelled()->create(['created_by' => $user->id]);
    $inactiveCampaign = Campaign::factory()->inactive()->create(['created_by' => $user->id]);

    expect($activeCampaign->status)->toBe(CampaignStatus::ACTIVE->value);
    expect($completedCampaign->status)->toBe(CampaignStatus::COMPLETED->value);
    expect($cancelledCampaign->status)->toBe(CampaignStatus::CANCELLED->value);
    expect($inactiveCampaign->status)->toBe(CampaignStatus::INACTIVE->value);
});

test('campaign can be created with different categories', function () {
    $user = User::factory()->admin()->create();
    
    $environmentCampaign = Campaign::factory()->environment()->create(['created_by' => $user->id]);
    $educationCampaign = Campaign::factory()->education()->create(['created_by' => $user->id]);
    $healthCampaign = Campaign::factory()->health()->create(['created_by' => $user->id]);

    expect($environmentCampaign->category)->toBe('environment');
    expect($educationCampaign->category)->toBe('education');
    expect($healthCampaign->category)->toBe('health');
});

test('campaign has relationship with creator', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->create(['created_by' => $user->id]);

    expect($campaign->createdBy)->toBeInstanceOf(User::class);
    expect($campaign->createdBy->id)->toBe($user->id);
});

test('campaign can check if it is active', function () {
    $user = User::factory()->admin()->create();
    
    $activeCampaign = Campaign::factory()->active()->create(['created_by' => $user->id]);
    $pendingCampaign = Campaign::factory()->create(['created_by' => $user->id]);

    expect($activeCampaign->isActive())->toBeTrue();
    expect($pendingCampaign->isActive())->toBeFalse();
});

test('campaign can check if it has reached its goal', function () {
    $user = User::factory()->admin()->create();
    
    $goalReachedCampaign = Campaign::factory()->goalReached()->create(['created_by' => $user->id]);
    $normalCampaign = Campaign::factory()->create(['created_by' => $user->id]);

    expect($goalReachedCampaign->hasReachedGoal())->toBeTrue();
    expect($normalCampaign->hasReachedGoal())->toBeFalse();
});

test('campaign calculates progress percentage correctly', function () {
    $user = User::factory()->admin()->create();
    
    // Campaign with 50% progress
    $campaign = Campaign::factory()->create([
        'goal_amount' => 1000.00,
        'current_amount' => 500.00,
        'created_by' => $user->id,
    ]);

    expect($campaign->progress_percentage)->toBe(50.0);
});

test('campaign progress percentage is capped at 100', function () {
    $user = User::factory()->admin()->create();
    
    // Campaign that exceeded its goal
    $campaign = Campaign::factory()->create([
        'goal_amount' => 1000.00,
        'current_amount' => 1500.00,
        'created_by' => $user->id,
    ]);

    expect($campaign->progress_percentage)->toBe(100.0);
});

test('campaign progress percentage returns 0 for zero goal', function () {
    $user = User::factory()->admin()->create();
    
    $campaign = Campaign::factory()->create([
        'goal_amount' => 0.00,
        'current_amount' => 500.00,
        'created_by' => $user->id,
    ]);

    expect($campaign->progress_percentage)->toBe(0.0);
});

test('campaign can have donations relationship', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->create(['created_by' => $user->id]);

    expect($campaign->donations)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    expect($campaign->donations)->toHaveCount(0);
});

test('campaign has proper date casting', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->create(['created_by' => $user->id]);

    expect($campaign->start_date)->toBeInstanceOf(\Carbon\Carbon::class);
    expect($campaign->end_date)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('campaign has proper decimal casting', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->create([
        'goal_amount' => 1000.50,
        'current_amount' => 250.75,
        'created_by' => $user->id,
    ]);

    expect($campaign->goal_amount)->toBe('1000.50');
    expect($campaign->current_amount)->toBe('250.75');
});

test('campaign can be created with progress', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->withProgress()->create(['created_by' => $user->id]);

    expect($campaign->current_amount)->toBeGreaterThan(0);
    expect($campaign->current_amount)->toBeLessThan($campaign->goal_amount);
    expect($campaign->status)->toBe(CampaignStatus::ACTIVE->value);
});

test('campaign can be created with image url', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->create([
        'image_url' => 'https://example.com/image.jpg',
        'created_by' => $user->id,
    ]);

    expect($campaign->image_url)->toBe('https://example.com/image.jpg');
});

test('campaign can be created without image url', function () {
    $user = User::factory()->admin()->create();
    $campaign = Campaign::factory()->create([
        'image_url' => null,
        'created_by' => $user->id,
    ]);

    expect($campaign->image_url)->toBeNull();
}); 