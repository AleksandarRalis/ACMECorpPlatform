<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * List the users.
     */
    public function list(): array
    {
        return UserResource::collection($this->userRepository->list())->response()->getData(true);
    }

    /**
     * Create a user.
     */
    public function create(UserDTO $userDTO): User
    {
        return $this->userRepository->create($userDTO);
    }

    /**
     * Update a user.
     */
    public function update(UserDTO $userDTO, User $user): User
    {
        return $this->userRepository->update($userDTO, $user);
    }

    /**
     * Destroy a user.
     */
    public function destroy(User $user): void
    {
        $this->userRepository->destroy($user);
    }
}