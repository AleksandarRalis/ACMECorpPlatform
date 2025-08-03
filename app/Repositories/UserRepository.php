<?php

namespace App\Repositories;

use App\DTO\UserDTO;
use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\Pagination;

class UserRepository implements UserRepositoryInterface
{
    /**
     * List all users.
     */
    public function list(): LengthAwarePaginator
    {
        return User::with('role')->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    /**
     * Create a user.
     */
    public function create(UserDTO $userDTO): User
    {
        return User::create($userDTO->toArray())->load('role');
    }

    /**
     * Update a user.
     */
    public function update(UserDTO $userDTO, User $user): User
    {
        $user->update($userDTO->toArray());
        return $user;
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): void
    {
        $user->delete();
    }

    /**
     * Find a user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}