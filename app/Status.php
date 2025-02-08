<?php

namespace App;

enum Status: string
{
    case FINISH = 'finish';
    case PENDING = 'pending';
    case CANCEL = 'cancel';
    case ACTIVE = 'active';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
