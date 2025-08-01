<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpsertUserRequest;

class UsersController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * List all users.
     */
    public function index(): JsonResponse
    {
        return new JsonResponse($this->userService->list());
    }
    
    /**
     * Register a new user.
     */
    public function store(UpsertUserRequest $request): JsonResponse
    {
        $userDTO = UserDTO::fromRequest($request);
        $user = $this->userService->create($userDTO);

        return new JsonResponse([
            'message' => 'User registered successfully. Please login to continue.',
            'user' => $user,
        ], 201);
    }

    /**
     * Update a user.
     */
    public function update(UpsertUserRequest $request, User $user): JsonResponse
    {
        $userDTO = UserDTO::fromUpdateRequest($request, $user);
        $this->userService->update($userDTO, $user);
        return new JsonResponse(['message' => 'User updated successfully']);
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): JsonResponse
    {
        if (Auth::user()->role->id === UserRole::ADMIN->id()) {
           return new JsonResponse(['message' => 'You are not authorized to delete admins'], 403);
        }

        $this->userService->destroy($user);
        return new JsonResponse(['message' => 'User deleted successfully']);
    }


    /**
     * Get authenticated user with role for frontend navigation
     */
    public function user(Request $request): JsonResponse
    {
        return new JsonResponse([
            'user' => $request->user()->load('role'),
        ]);
    }
} 