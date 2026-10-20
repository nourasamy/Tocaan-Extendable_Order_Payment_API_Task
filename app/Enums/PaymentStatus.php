<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case Pending = 1;
    case Successful = 2;
    case Failed = 3;

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Successful => 'Successful',
            self::Failed => 'Failed',
        };
    }
}
