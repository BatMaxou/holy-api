<?php

namespace App\Enum;

enum HolyTierEnum: string
{
    case S = 'holyshit';
    case A = 'holyrique';
    case B = 'holystique';
    case C = 'holymite';
    case D = 'holymonde';
    case UNRANKED = 'unranked';

    /**
     * @return array{
     *  ranks: self[],
     *  default: self
     * }
     */
    public static function getOrdered(): array
    {
        return [
            'ranks' => [
                self::S,
                self::A,
                self::B,
                self::C,
                self::D,
            ],
            'default' => self::UNRANKED,
        ];
    }
}
