<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return new JsonResponse(['message' => 'Unauthorized'], 401);
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return new JsonResponse(['message' => 'Access denied. Admin privileges required.'], 403);
        }

        // Check if admin is trying to become an employee
        if($request->route('user')?->isAdmin() && $request->role === UserRole::EMPLOYEE->value){
            return new JsonResponse(['message' => 'Admin cannot be an employee'], 403);
        }

        return $next($request);
    }
} 