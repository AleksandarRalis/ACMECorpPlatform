<?php

namespace App\Interfaces;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function list(): LengthAwarePaginator;
    public function create(UserDTO $userDTO): User;
    public function update(UserDTO $userDTO, User $user): User;
    public function destroy(User $user): void;
    public function findByEmail(string $email): ?User;
}