<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpsertCampaignRequest;
use App\DTO\CampaignDTO;
use App\Services\CampaignService;

class CampaignsController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService
    ) {}
    /**
     * Display a listing of campaigns.
     */
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse($this->campaignService->list());
    }

    /**
     * Store a newly created campaign.
     */
    public function store(UpsertCampaignRequest $request): JsonResponse
    {
        $campaignDTO = CampaignDTO::fromRequest($request);
        $campaign = $this->campaignService->create($campaignDTO);
        return new JsonResponse($campaign);
    }

    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign): JsonResponse
    {
        return new JsonResponse($this->campaignService->show($campaign->id));
    }

    /**
     * Update the specified campaign.
     */
    public function update(Request $request, Campaign $campaign): JsonResponse
    {
        
    }

    /**
     * Remove the specified campaign.
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        
    }

    /**
     * Get campaigns created by the authenticated user.
     */
    public function listCampaignsByUser(): JsonResponse
    {
     
    }
} 