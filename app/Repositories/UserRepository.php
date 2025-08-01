<?php

namespace App\Repositories;

use App\DTO\UserDTO;
use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\Pagination;

class UserRepository implements UserRepositoryInterface
{
    public function list(): LengthAwarePaginator
    {
        return User::with('role')->paginate(Pagination::DEFAULT_LIMIT->value);
    }

    public function create(UserDTO $userDTO): User
    {
        return User::create($userDTO->toArray())->load('role');
    }

    public function update(UserDTO $userDTO, User $user): User
    {
        $user->update($userDTO->toArray());
        return $user;
    }

    public function destroy(User $user): void
    {
        $user->delete();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}