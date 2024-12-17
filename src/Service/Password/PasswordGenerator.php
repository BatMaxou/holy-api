<?php

namespace App\Service\Password;

class PasswordGenerator
{
    private const CHAR = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    private const MAJ = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    private const SPEC_CAR = ['!', '@', '#', '$', '%', '&', '*', '(', ')', '-', '_', '+', '=', '\\', ':', ';', '<', '>', ',', '.', '?'];

    /**
     * @param string[] $from
     */
    public static function pickFrom(array $from): string
    {
        return $from[rand(0, count($from) - 1)];
    }

    public static function pickCar(): string
    {
        return self::pickFrom(self::CHAR);
    }

    public static function pickMaj(): string
    {
        return self::pickFrom(self::MAJ);
    }

    public static function pickSpecCar(): string
    {
        return self::pickFrom(self::SPEC_CAR);
    }

    public static function pickNb(): string
    {
        return (string) rand(0, 9);
    }

    public static function pickOne(): string
    {
        return match (rand(1, 12)) {
            1, 2, 3, 4, 5 => self::pickCar(),
            6, 7 => self::pickMaj(),
            8, 9, 10 => self::pickNb(),
            default => self::pickSpecCar(),
        };
    }

    public static function generate(int $length = 20): string
    {
        $password = '';
        for ($i = 0; $i < $length; ++$i) {
            $password .= self::pickOne();
        }

        return $password;
    }
}
