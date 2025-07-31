<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Campaign;

class CampaignAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get campaign from route parameter
        $campaign = $request->route('campaign');
        
        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        // Only allow users to update or delete their own campaigns
        if ($campaign->created_by == $user->id) {
            return $next($request);
        }
        
        return response()->json(['message' => 'You can only modify your own campaigns'], 403);
    }
}
