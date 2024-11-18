<?php

namespace App\Entity;

use App\DTO\Interface\DTOInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Flavour extends Product
{
    #[ORM\Column(length: 255)]
    private ?string $flavour = null;

    public function getFlavour(): ?string
    {
        return $this->flavour;
    }

    public function setFlavour(string $flavour): static
    {
        $this->flavour = $flavour;

        return $this;
    }

    public static function isEntitySatisfiedByDTO(DTOInterface $dto): bool
    {
        return parent::isEntitySatisfiedByDTO($dto) && isset($dto->flavour) && null !== $dto->flavour;
    }

    public static function createFromDTO(DTOInterface $dto): static
    {
        return parent::createFromDTO($dto)
            ->setFlavour($dto->flavour) // @phpstan-ignore-line
        ;
    }
}
