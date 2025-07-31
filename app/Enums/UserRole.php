<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';

    /**
     * Get the display name for the role.
     */
    public function displayName(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::EMPLOYEE => 'Employee',
        };
    }

    public function id(): int
{
    return match($this) {
        self::ADMIN    => 1,
        self::EMPLOYEE => 2,
    };
}


    /**
     * Check if the role is admin.
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public static function fromName(string $name): int
    {
        return match($name) {
            self::ADMIN->value => 1,
            self::EMPLOYEE->value => 2,
        };
    }
} 