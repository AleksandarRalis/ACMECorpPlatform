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
use App\Http\Resources\CampaignResource;

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
        $filters = $request->only(['search', 'category']);
        return new JsonResponse($this->campaignService->list($filters));
    }

    /** 
     * List all campaigns admin only, active and inactive
     */
    public function listAll(): JsonResponse
    {
        return new JsonResponse($this->campaignService->listAll());
    }

    /**
     * Store a newly created campaign.
     */
    public function store(UpsertCampaignRequest $request): JsonResponse
    {
        $campaignDTO = CampaignDTO::fromRequest($request);
        $campaign = $this->campaignService->create($campaignDTO);
        return new JsonResponse($campaign, 201);
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
    public function update(UpsertCampaignRequest $request, Campaign $campaign): JsonResponse
    {
        $campaignDTO = CampaignDTO::fromRequestForUpdate($request, $campaign);
        $campaign = $this->campaignService->update($campaignDTO, $campaign);
        return new JsonResponse($campaign);
    }

    /**
     * Remove the specified campaign.
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        $this->campaignService->destroy($campaign);
        return new JsonResponse(['message' => 'Campaign deleted successfully']);
    }

    /**
     * Activate a campaign (admin only).
     */
    public function activate(Campaign $campaign): JsonResponse
    {
        $this->campaignService->activate($campaign);
        return new JsonResponse(['message' => 'Campaign activated successfully']);
    }

    /**
     * Get campaigns created by the authenticated user.
     */
    public function myCampaigns(Request $request): JsonResponse
    {
        $user = Auth::user();
        $filters = $request->only(['search', 'status']);
        $campaigns = $this->campaignService->listByUser($user, $filters);
        return new JsonResponse(CampaignResource::collection($campaigns));
    }
} 