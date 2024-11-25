<?php

namespace App\Enum;

enum HolyTierEnum: string
{
    case S = 'holyshit';
    case A = 'holyrique';
    case B = 'holystique';
    case C = 'holymite';
    case D = 'holymonde';

    /**
     * @return array<self>
     */
    public static function getOrdered(): array
    {
        return [
            self::S,
            self::A,
            self::B,
            self::C,
            self::D,
        ];
    }
}
