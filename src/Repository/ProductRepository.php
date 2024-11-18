<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(?Product $product = null, bool $flush = true): void
    {
        $em = $this->getEntityManager();

        if (null !== $product) {
            $em->persist($product);
        }

        if ($flush || null === $product) {
            $em->flush();
        }
    }
}
