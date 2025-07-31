<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UpsertUserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Services\AuthService;
use App\DTO\UserDTO;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected AuthService $authService
    ) {}

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
     * Login user and create token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->authenticate($request->email, $request->password);
        $token = $user->createToken('auth_token')->plainTextToken;

        return new JsonResponse([
            'message' => 'Login successful',
            'user' => $user->load('role'),
            'token' => $token,
        ]);
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Get authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load('role'),
        ]);
    }
} 