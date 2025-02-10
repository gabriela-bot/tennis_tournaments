<?php

namespace App\Enum;

enum Status: string
{
    case FINISH = 'done';
    case PENDING = 'pending';
    case CANCEL = 'cancel';
    case ACTIVE = 'active';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
