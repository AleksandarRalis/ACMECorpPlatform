<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpsertUserRequest;
use App\DTO\UserDTO;

class UsersController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}

    public function index(): JsonResponse
    {
        return new JsonResponse($this->userService->list());
    }

    public function update(UpsertUserRequest $request, User $user): JsonResponse
    {
        $userDTO = UserDTO::fromUpdateRequest($request, $user);
        $this->userService->update($userDTO, $user);
        return new JsonResponse(['message' => 'User updated successfully']);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->destroy($user);
        return new JsonResponse(['message' => 'User deleted successfully']);
    }
} 