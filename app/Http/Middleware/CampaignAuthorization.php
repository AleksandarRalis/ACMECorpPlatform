<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
            return new JsonResponse(['message' => 'Unauthorized'], 401);
        }

        $campaign = $request->route('campaign');
        
        if (!$campaign) {
            return new JsonResponse(['message' => 'Campaign not found'], 404);
        }

        // Only allow users to update or delete their own campaigns
        if ($campaign->created_by == $user->id) {
            return $next($request);
        }
        
            return new JsonResponse(['message' => 'You can only modify your own campaigns'], 403);
    }
}
