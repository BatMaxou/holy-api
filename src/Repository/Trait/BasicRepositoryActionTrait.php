<?php

namespace App\Repository\Trait;

/**
 * @template T of object
 */
trait BasicRepositoryActionTrait
{
    /**
     * @param T|null $product
     */
    public function save(?object $product = null, bool $flush = true): void
    {
        $em = $this->getEntityManager();

        if (null !== $product) {
            $em->persist($product);
        }

        if ($flush || null === $product) {
            $em->flush();
        }
    }

    /**
     * @param T $entity
     */
    public function remove(object $entity): void
    {
        $em = $this->getEntityManager();

        $em->remove($entity);
        $em->flush();
    }
}
