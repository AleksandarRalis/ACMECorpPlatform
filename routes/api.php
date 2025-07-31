<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignsController;
use App\Http\Controllers\Api\DonationsController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/register', [AuthController::class, 'store']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Campaign routes
    Route::get('/campaigns', [CampaignsController::class, 'index']);
    Route::get('/campaigns/my', [CampaignsController::class, 'myCampaigns']);
    Route::post('/campaigns', [CampaignsController::class, 'store']);
    Route::get('/campaigns/{campaign}', [CampaignsController::class, 'show']);
    Route::put('/campaigns/{campaign}', [CampaignsController::class, 'update'])->middleware('campaign.auth');
    Route::delete('/campaigns/{campaign}', [CampaignsController::class, 'destroy'])->middleware('campaign.auth');

    // Donation routes
    Route::get('/donations', [DonationsController::class, 'index']); 
    Route::get('/donations/my-donations', [DonationsController::class, 'myDonations']);
    Route::post('/donations/{campaign}', [DonationsController::class, 'store']); 
    Route::get('/donations/{donation}', [DonationsController::class, 'show']);

    // Admin routes (admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index']);
        Route::get('/admin/campaigns/{campaign}/stats', [AdminController::class, 'campaignStats']);
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
        
        // Admin campaign management routes
        Route::put('/admin/campaigns/{campaign}', [CampaignsController::class, 'update']);
        Route::delete('/admin/campaigns/{campaign}', [CampaignsController::class, 'destroy']);
        Route::post('/admin/campaigns/{campaign}/activate', [CampaignsController::class, 'activate']);
        Route::get('/admin/campaigns/all', [CampaignsController::class, 'listAll']);
        
        // Admin user management routes
        Route::get('/admin/users', [UsersController::class, 'index']);
        Route::put('/admin/users/{user}', [UsersController::class, 'update']);
        Route::delete('/admin/users/{user}', [UsersController::class, 'destroy']);
    });
});

// Legacy route (keeping for compatibility)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 