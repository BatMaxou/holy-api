<?php

namespace App\Entity;

use App\Enum\HolyTierEnum;
use App\Repository\RankedProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RankedProductRepository::class)]
class RankedProduct
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private string $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private TierList $tierList;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\Column(enumType: HolyTierEnum::class, nullable: true)]
    private ?HolyTierEnum $tier = null;

    public function __construct(
        TierList $tierList,
        Product $product,
    ) {
        $this->id = Uuid::v4();
        $this->product = $product;
        $this->tierList = $tierList;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTierList(): TierList
    {
        return $this->tierList;
    }

    public function setTierList(TierList $tierList): static
    {
        $this->tierList = $tierList;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getTier(): ?HolyTierEnum
    {
        return $this->tier;
    }

    public function setTier(HolyTierEnum $tier): static
    {
        $this->tier = $tier;

        return $this;
    }
}
