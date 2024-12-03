<?php

namespace App\Repository;

use App\Entity\RankedProduct;
use App\Entity\TierList;
use App\Enum\HolyTierEnum;
use App\Repository\Trait\BasicRepositoryActionTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RankedProduct>
 */
class RankedProductRepository extends ServiceEntityRepository
{
    /**
     * @use BasicRepositoryActionTrait<RankedProduct>
     */
    use BasicRepositoryActionTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RankedProduct::class);
    }

    /**
     * @return RankedProduct[]
     */
    public function findByTierList(TierList $tierList): array
    {
        return $this->createQueryBuilder('r') // @phpstan-ignore return.type
            ->where('r.tierList = :tierList')
            ->setParameter('tierList', $tierList)
            ->addOrderBy('r.orderNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return RankedProduct[]
     */
    public function findWithOrderBetween(TierList $tierList, HolyTierEnum $tier, int $min, int $max): array
    {
        return $this->findWith(
            $tierList,
            $tier,
            fn (QueryBuilder $queryBuilder): QueryBuilder => $queryBuilder
                ->andWhere('r.orderNumber BETWEEN :min AND :max')
                ->setParameter('min', $min)
                ->setParameter('max', $max)
        );
    }

    /**
     * @return RankedProduct[]
     */
    public function findWithOrderGreater(TierList $tierList, HolyTierEnum $tier, int $order): array
    {
        return $this->findWith(
            $tierList,
            $tier,
            fn (QueryBuilder $queryBuilder): QueryBuilder => $queryBuilder
                ->andWhere('r.orderNumber > :order')
                ->setParameter('order', $order)
        );
    }

    /**
     * @return RankedProduct[]
     */
    public function findWithOrderGreaterOrEqualTo(TierList $tierList, HolyTierEnum $tier, int $order): array
    {
        return $this->findWith(
            $tierList,
            $tier,
            fn (QueryBuilder $queryBuilder): QueryBuilder => $queryBuilder
                ->andWhere('r.orderNumber >= :order')
                ->setParameter('order', $order)
        );
    }

    /**
     * @param callable(QueryBuilder):QueryBuilder $withConditions
     *
     * @return RankedProduct[]
     */
    private function findWith(TierList $tierList, HolyTierEnum $tier, ?callable $withConditions = null): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.tierList = :tierList')
            ->andWhere('r.tier = :tier')
            ->setParameter('tierList', $tierList)
            ->setParameter('tier', $tier);

        return (null === $withConditions // @phpstan-ignore return.type
            ? $queryBuilder
            : $withConditions($queryBuilder)
        )->getQuery()->getResult();
    }
}
