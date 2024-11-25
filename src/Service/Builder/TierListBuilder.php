<?php

namespace App\Service\Builder;

use App\Entity\TierList;
use App\Entity\RankedProduct;
use App\Repository\ProductRepository;
use App\Repository\TierListRepository;
use App\Repository\RankedProductRepository;

class TierListBuilder
{
    public function __construct(
        private readonly TierListRepository $tierListRepository,
        private readonly ProductRepository $productRepository,
        private readonly RankedProductRepository $rankedProductRepository
    ) {
    }

    public function build(): void
    {
        $build = new TierList();
        $this->tierListRepository->save($build);

        $flavours = $this->productRepository->findAllFlavour();
        foreach ($flavours as $flavour) {
            $this->rankedProductRepository->save(new RankedProduct($build, $flavour), false);
        }

        $this->rankedProductRepository->save();
    }
}
