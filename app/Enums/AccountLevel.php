<?php

namespace App\Enums;

enum AccountLevel: string
{
    case Admin = 'Admin';
    case Staff = 'Staff';

    /**
     * Get a human-readable label for the account level.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Staff => 'Warehouse Staff',
        };
    }
}
