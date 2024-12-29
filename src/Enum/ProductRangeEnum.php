<?php

namespace App\Enum;

enum ProductRangeEnum: string
{
    case DISCOVER_PACK = 'discover-pack';
    case ENERGY = 'energy';
    case ICED_TEA = 'iced-tea';
    case HYDRATION = 'hydration';
    case MILKSHAKE = 'milkshake';
    case SHAKER = 'shaker';
    case MERCH = 'merch';

    /**
     * @return string[]
     */
    public static function getAll(): array
    {
        $productRanges = [];
        foreach (ProductRangeEnum::cases() as $productRange) {
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
            ProductRangeEnum::ENERGY,
            ProductRangeEnum::ICED_TEA,
            ProductRangeEnum::HYDRATION,
            ProductRangeEnum::MILKSHAKE,
        ];
    }
}
