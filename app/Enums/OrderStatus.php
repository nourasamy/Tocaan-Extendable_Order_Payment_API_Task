<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 1;
    case Confirmed = 2;
    case Cancelled = 3;

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
        };
    }
}
