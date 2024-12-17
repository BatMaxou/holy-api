<?php

namespace App\Entity;

use App\DTO\ScrapProductDTO;
use App\Enum\ProductRangeEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\DTO\Interface\DTOInterface;
use App\Repository\ProductRepository;
use App\Entity\Interface\DTOLinkedEntityInterface;
use App\Entity\Trait\UuidTrait;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap(['product' => Product::class, 'flavour' => Flavour::class])]
class Product implements DTOLinkedEntityInterface
{
    use UuidTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $imageUrl = null;

    #[ORM\Column(enumType: ProductRangeEnum::class)]
    private ?ProductRangeEnum $productRange = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getProductRange(): ?ProductRangeEnum
    {
        return $this->productRange;
    }

    public function setProductRange(ProductRangeEnum $productRange): static
    {
        $this->productRange = $productRange;

        return $this;
    }

    public static function isEntitySatisfiedByDTO(DTOInterface $dto): bool
    {
        return $dto instanceof ScrapProductDTO;
    }

    public static function createFromDTO(DTOInterface $dto): static
    {
        if (!static::isEntitySatisfiedByDTO($dto)) {
            throw new \InvalidArgumentException('DTO is not satisfied by this entity');
        }

        return (new static()) // @phpstan-ignore-line
            ->setName($dto->name) // @phpstan-ignore-line
            ->setPrice($dto->price) // @phpstan-ignore-line
            ->setImageUrl($dto->imageUrl) // @phpstan-ignore-line
            ->setProductRange($dto->productRange) // @phpstan-ignore-line
        ;
    }
}
