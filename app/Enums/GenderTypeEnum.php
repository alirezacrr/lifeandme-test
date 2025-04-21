<?php

namespace App\Enums;

enum GenderTypeEnum: string
{
    case FEMALE = 'female';
    case MALE = 'male';

    public function getLabel(): string
    {
        return match($this) {
            self::FEMALE => 'زن',
            self::MALE => 'مرد',
        };
    }
}
