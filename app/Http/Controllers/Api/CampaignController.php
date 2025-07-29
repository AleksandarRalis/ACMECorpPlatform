<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Campaign::with(['creator', 'donations']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by featured
        if ($request->has('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['title', 'goal_amount', 'current_amount', 'created_at', 'end_date'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $campaigns = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Store a newly created campaign.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'goal_amount' => 'required|numeric|min:1',
            'image_url' => 'nullable|url|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $campaign = Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'current_amount' => 0,
            'image_url' => $request->image_url,
            'status' => 'active',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_featured' => false,
            'view_count' => 0,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Campaign created successfully',
            'campaign' => $campaign->load('creator'),
        ], 201);
    }

    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign): JsonResponse
    {
        // Increment view count
        $campaign->increment('view_count');

        return response()->json([
            'campaign' => $campaign->load(['creator', 'donations.donor']),
        ]);
    }

    /**
     * Update the specified campaign.
     */
    public function update(Request $request, Campaign $campaign): JsonResponse
    {
        // Check if user can edit this campaign
        if ($campaign->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized to edit this campaign',
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:5000',
            'goal_amount' => 'sometimes|required|numeric|min:1',
            'image_url' => 'nullable|url|max:500',
            'status' => 'sometimes|required|in:draft,active,paused,completed',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'is_featured' => 'sometimes|boolean',
        ]);

        $campaign->update($request->only([
            'title', 'description', 'goal_amount', 'image_url', 
            'status', 'start_date', 'end_date', 'is_featured'
        ]));

        return response()->json([
            'message' => 'Campaign updated successfully',
            'campaign' => $campaign->load('creator'),
        ]);
    }

    /**
     * Remove the specified campaign.
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        // Check if user can delete this campaign
        if ($campaign->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized to delete this campaign',
            ], 403);
        }

        $campaign->delete();

        return response()->json([
            'message' => 'Campaign deleted successfully',
        ]);
    }

    /**
     * Get campaigns created by the authenticated user.
     */
    public function myCampaigns(): JsonResponse
    {
        $campaigns = Campaign::where('created_by', Auth::id())
            ->with(['donations'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'campaigns' => $campaigns,
        ]);
    }
} 