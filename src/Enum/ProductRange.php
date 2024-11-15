<?php

namespace App\Enum;

enum ProductRange: string
{
    case DISCOVER_PACK = 'discover-pack';
    case ENERGY = 'energy';
    case ICED_TEA = 'iced-tea';
    case HYDRATION = 'hydration';
    case SHAKER = 'shaker';
    case MERCH = 'merch';

    /**
     * @return string[]
     */
    public static function getAll(): array
    {
        $productRanges = [];
        foreach (ProductRange::cases() as $productRange) {
            $productRanges[] = $productRange->value;
        }

        return $productRanges;
    }

    /**
     * @return self[]
     */
    public static function getAllWithFlavour(): array
    {
        return [
            ProductRange::ENERGY,
            ProductRange::ICED_TEA,
            ProductRange::HYDRATION,
        ];
    }
}
