<?php

namespace App\Repository;

use App\Entity\Flavour;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\BasicRepositoryActionTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * @use BasicRepositoryActionTrait<Product>
     */
    use BasicRepositoryActionTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Flavour[]
     */
    public function findAllFlavour(): array
    {
        return $this->createQueryBuilder('p') // @phpstan-ignore return.type
            ->where('p INSTANCE OF App\Entity\Flavour')
            ->getQuery()
            ->getResult();
    }
}
