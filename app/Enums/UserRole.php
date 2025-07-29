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

    /**
     * Check if the role is admin.
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Check if the role is employee.
     */
    public function isEmployee(): bool
    {
        return $this === self::EMPLOYEE;
    }

    /**
     * Get all available roles.
     */
    public static function all(): array
    {
        return [
            self::ADMIN,
            self::EMPLOYEE,
        ];
    }

    /**
     * Get the default role for new users.
     */
    public static function default(): self
    {
        return self::EMPLOYEE;
    }
} 