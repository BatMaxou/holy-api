<?php

namespace App\Service\Factory;

use App\Entity\TierList;
use App\Entity\RankedProduct;
use App\Repository\ProductRepository;
use App\Repository\TierListRepository;
use App\Repository\RankedProductRepository;

class TierListFactory
{
    public function __construct(
        private readonly TierListRepository $tierListRepository,
        private readonly ProductRepository $productRepository,
        private readonly RankedProductRepository $rankedProductRepository
    ) {
    }

    public function create(): void
    {
        $new = new TierList();
        $this->tierListRepository->save($new);

        $flavours = $this->productRepository->findAllFlavour();
        foreach ($flavours as $flavour) {
            $this->rankedProductRepository->save(new RankedProduct($new, $flavour), false);
        }

        $this->rankedProductRepository->save();
    }
}
