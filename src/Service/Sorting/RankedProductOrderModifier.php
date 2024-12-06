<?php

namespace App\Service\Sorting;

use App\Entity\RankedProduct;
use App\Entity\TierList;
use App\Enum\HolyTierEnum;
use App\Repository\RankedProductRepository;

class RankedProductOrderModifier
{
    public function __construct(
        private readonly RankedProductRepository $repository
    ) {
    }

    public function modifyBetween(TierList $tierList, HolyTierEnum $tier, RankedProduct $current, ?int $oldOrder, int $order): void
    {
        if (null === $oldOrder) {
            $this->insert($tierList, $tier, $current, $order);

            return;
        }

        $this->modify(
            fn () => $this->repository->findWithOrderBetween($tierList, $tier, min($oldOrder, $order), max($oldOrder, $order)),
            $oldOrder < $order ? -1 : 1,
            $current
        );
    }

    public function pop(TierList $tierList, HolyTierEnum $tier, RankedProduct $current, int $order): void
    {
        $this->modify(
            fn () => $this->repository->findWithOrderGreater($tierList, $tier, $order),
            -1,
            $current
        );
    }

    public function insert(TierList $tierList, HolyTierEnum $tier, RankedProduct $current, int $order): void
    {
        $this->modify(
            fn () => $this->repository->findWithOrderGreaterOrEqualTo($tierList, $tier, $order),
            1,
            $current
        );
    }

    /**
     * @param callable():RankedProduct[] $dataSearcher
     */
    private function modify(callable $dataSearcher, int $step, RankedProduct $current): void
    {
        foreach ($dataSearcher() as $product) {
            if ($product->getId() === $current->getId()) {
                continue;
            }

            $product->setOrderNumber($product->getOrderNumber() + $step);
        }
    }
}
