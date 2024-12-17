<?php

namespace App\Entity;

use App\Entity\Trait\UuidTrait;
use App\Enum\HolyTierEnum;
use App\Repository\RankedProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankedProductRepository::class)]
class RankedProduct
{
    use UuidTrait {
        __construct as initializeUuid;
    }

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private TierList $tierList;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\Column(enumType: HolyTierEnum::class)]
    private HolyTierEnum $tier;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $orderNumber = null;

    public function __construct(
        TierList $tierList,
        Product $product,
    ) {
        $this->initializeUuid();
        $this->product = $product;
        $this->tierList = $tierList;
        $this->tier = HolyTierEnum::UNRANKED;
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

    public function getTier(): HolyTierEnum
    {
        return $this->tier;
    }

    public function setTier(HolyTierEnum $tier): static
    {
        $this->tier = $tier;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }
}
