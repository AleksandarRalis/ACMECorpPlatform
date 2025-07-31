<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function id(): int
    {
        return match($this) {
            self::ACTIVE => 1,
            self::INACTIVE => 0,
        };
    }
}