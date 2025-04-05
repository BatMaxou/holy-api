<?php

namespace App\Service\Guesser;

use App\Enum\ProductRangeEnum;

class ProductRangeGuesser
{
    public function guess(string $compareFrom): ProductRangeEnum
    {
        $productRanges = ProductRangeEnum::getAll();

        $closest = $this->searchClosest($productRanges, $compareFrom);
        if (null === $closest) {
            return ProductRangeEnum::DEFAULT;
        }

        return ProductRangeEnum::from($closest);
    }

    /**
     * @param string[] $productRanges
     */
    private function searchClosest(array $productRanges, string $compareFrom): ?string
    {
        $shortest = -1;
        $closest = null;

        foreach ($productRanges as $productRange) {
            $lev = levenshtein(strtolower($compareFrom), $productRange);
            if (0 == $lev) {
                return $productRange;
            }

            if (($lev <= $shortest || $shortest < 0) && $this->contains($productRange, $compareFrom)) {
                $closest = $productRange;
                $shortest = $lev;
            }
        }

        return $closest;
    }

    private function contains(string $productRange, string $compareFrom): bool
    {
        $composedRange = explode('-', $productRange);
        $composedCompareFrom = explode(' ', $compareFrom);

        foreach ($composedCompareFrom as $compareFromWord) {
            foreach ($composedRange as $productRangeWord) {
                if (str_contains(strtolower($compareFromWord), $productRangeWord)) {
                    return true;
                }
            }
        }

        return false;
    }
}
