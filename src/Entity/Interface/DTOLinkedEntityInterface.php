<?php

namespace App\Entity\Interface;

use App\DTO\Interface\DTOInterface;

interface DTOLinkedEntityInterface
{
    public static function isEntitySatisfiedByDTO(DTOInterface $dto): bool;

    public static function createFromDTO(DTOInterface $dto): static;
}
