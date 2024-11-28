<?php

namespace App\Repository;

use App\Entity\WeekScrap;
use App\Repository\Trait\BasicRepositoryActionTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeekScrap>
 */
class WeekScrapRepository extends ServiceEntityRepository
{
    /**
     * @use BasicRepositoryActionTrait<WeekScrap>
     */
    use BasicRepositoryActionTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeekScrap::class);
    }

    public function findLast(): ?WeekScrap
    {
        return $this->createQueryBuilder('w') // @phpstan-ignore return.type
            ->orderBy('w.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
