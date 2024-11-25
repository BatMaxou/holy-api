<?php

namespace App\Repository;

use App\Entity\RankedProduct;
use App\Entity\TierList;
use App\Repository\Trait\BasicRepositoryActionTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            ->getQuery()
            ->getResult();
    }
}
