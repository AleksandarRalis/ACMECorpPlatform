<?php

namespace App\DTO;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpsertUserRequest;

class UserDTO
{
    public function __construct(
        public string $employee_id,
        public string $name,
        public string $email,
        public string $department,
        public string $position,
        public string $is_active,
        public string $phone,
        public ?string $password,
        public int $role_id,
    ) {}

    public static function fromRequest(UpsertUserRequest $request): UserDTO
    {
        return new self(
           employee_id: Str::random(5),
           name: $request->name,
           email: $request->email,
           department: $request->department,
           position: $request->position,
           is_active: UserStatus::ACTIVE->id(),
           phone: $request->phone,
           password: $request->password,
           role_id: UserRole::EMPLOYEE->id(),
        );
    }

    public static function fromUpdateRequest(UpsertUserRequest $request, User $user): UserDTO
    {
        return new self(
            employee_id: $request->employee_id ?? $user->employee_id,
            name: $request->name ?? $user->name,
            email: $request->email ?? $user->email,
            department: $request->department ?? $user->department,
            position: $request->position ?? $user->position,
            is_active: $request->is_active ? UserStatus::ACTIVE->id() : UserStatus::INACTIVE->id(),
            phone: $request->phone ?? $user->phone,
            password: $request->password ? Hash::make($request->password) : null,
            role_id: UserRole::fromName($request->role),
        );
    }

    public function toArray(): array
    {
        $data = [
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'email' => $this->email,
            'department' => $this->department,
            'position' => $this->position,
            'is_active' => $this->is_active,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
        ];

        // Only include password if it's not null
        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        return $data;
    }

    
}