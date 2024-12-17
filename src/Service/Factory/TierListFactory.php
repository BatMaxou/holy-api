<?php

namespace App\Service\Factory;

use App\Entity\RankedProduct;
use App\Entity\TierList;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\RankedProductRepository;
use App\Repository\TierListRepository;

class TierListFactory
{
    public function __construct(
        private readonly TierListRepository $tierListRepository,
        private readonly ProductRepository $productRepository,
        private readonly RankedProductRepository $rankedProductRepository
    ) {
    }

    public function create(User $user): void
    {
        $new = (new TierList())->setUser($user);
        $this->tierListRepository->save($new);

        $flavours = $this->productRepository->findAllFlavour();
        foreach ($flavours as $flavour) {
            $this->rankedProductRepository->save(new RankedProduct($new, $flavour), false);
        }

        $this->rankedProductRepository->save();
    }
}
