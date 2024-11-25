<?php

namespace App\Repository;

use App\Entity\TierList;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\BasicRepositoryActionTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<TierList>
 */
class TierListRepository extends ServiceEntityRepository
{
    /**
     * @use BasicRepositoryActionTrait<TierList>
     */
    use BasicRepositoryActionTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TierList::class);
    }

    public function findOnlyOne(): ?TierList
    {
        return $this->createQueryBuilder('t') // @phpstan-ignore return.type
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
