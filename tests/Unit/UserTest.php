<?php

use App\Models\Role;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Create roles before each test to ensure they exist
    Role::factory()->admin()->create();
    Role::factory()->employee()->create();
});

test('database connection is working', function () {
    expect(DB::connection())->not->toBeNull();
});

test('user can be created with required fields', function () {
    $user = User::factory()->employee()->create();

    expect($user)
        ->name->not->toBeEmpty()
        ->email->not->toBeEmpty()
        ->department->not->toBeEmpty()
        ->position->not->toBeEmpty()
        ->is_active->toBeTrue()
        ->role_id->toBe(UserRole::EMPLOYEE->id());
});

test('user can be marked as inactive', function () {
    $user = User::factory()->inactive()->create();

    expect($user->is_active)->toBeFalse();
});

test('user role can be admin or employee', function () {
    $adminUser = User::factory()->admin()->create();
    $employeeUser = User::factory()->employee()->create();

    expect($adminUser->role_id)->toBe(UserRole::ADMIN->id());
    expect($employeeUser->role_id)->toBe(UserRole::EMPLOYEE->id());
});

test('user has required attributes', function () {
    $user = User::factory()->employee()->create();

    expect($user->employee_id)->toMatch('/^EMP\d{3}$/');
    expect($user->name)->not->toBeEmpty();
    expect($user->email)->not->toBeEmpty();
    expect($user->department)->not->toBeEmpty();
    expect($user->position)->not->toBeEmpty();
    expect($user->is_active)->toBeTrue();
    expect($user->phone)->not->toBeEmpty();
    expect($user->role_id)->toBe(UserRole::EMPLOYEE->id());
});

test('user admin check ', function () {
    $adminUser = User::factory()->admin()->create();
    $employeeUser = User::factory()->employee()->create();

    expect($adminUser->role->name)->toBe('admin');
    expect($employeeUser->role->name)->toBe('employee');
    expect($adminUser->role->id)->toBe(UserRole::ADMIN->id());
    expect($employeeUser->role->id)->toBe(UserRole::EMPLOYEE->id());
});

test('user can check if they are active', function () {
    $activeUser = User::factory()->active()->create();
    $inactiveUser = User::factory()->inactive()->create();

    expect($activeUser->is_active)->toBeTrue();
    expect($inactiveUser->is_active)->toBeFalse();
}); 