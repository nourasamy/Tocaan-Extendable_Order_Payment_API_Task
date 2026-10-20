<?php

namespace App\Enums;

enum TaxType: int
{
    case Fixed = 1;
    case Percent = 2;

    public function label(): string
    {
        return match($this) {
            self::Fixed => 'Fixed',
            self::Percent => 'Percent',
        };
    }
}
