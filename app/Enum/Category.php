<?php

namespace App\Enum;

enum Category: string
{
    case WOMEN = 'women';
    case MEN = 'men';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
