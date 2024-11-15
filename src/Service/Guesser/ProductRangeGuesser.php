<?php

namespace App\Service\Guesser;

use App\Enum\ProductRange;

class ProductRangeGuesser
{
    public function guess(string $compareFrom): ProductRange
    {
        $productRanges = ProductRange::getAll();

        $closest = $this->searchClosest($productRanges, $compareFrom);

        return ProductRange::from($closest);
    }

    /**
     * @param string[] $productRanges
     */
    private function searchClosest(array $productRanges, string $compareFrom): string
    {
        $shortest = -1;
        $closest = null;

        foreach ($productRanges as $productRange) {
            $lev = levenshtein($compareFrom, $productRange);

            if (0 == $lev) {
                return $productRange;
            }

            if ($lev <= $shortest || $shortest < 0) {
                $closest = $productRange;
                $shortest = $lev;
            }
        }

        if (null === $closest) {
            throw new \LogicException('No product range provided');
        }

        return $closest;
    }
}
