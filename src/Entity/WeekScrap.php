<?php

namespace App\Entity;

use App\Entity\Trait\UuidTrait;
use App\Enum\WeekScrapStatusEnum;
use App\Repository\WeekScrapRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeekScrapRepository::class)]
class WeekScrap
{
    use UuidTrait {
        __construct as initializeUuid;
    }

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default' => '0000-00-00 00:00:00'])]
    private \DateTimeImmutable $date;

    #[ORM\Column(options: ['default' => 0])]
    private int $productAdded;

    #[ORM\Column(enumType: WeekScrapStatusEnum::class)]
    private ?WeekScrapStatusEnum $status = null;

    /**
     * @var array<string|null>
     */
    #[ORM\Column(type: Types::JSON, options: ['default' => '[]'])]
    private array $details;

    public function __construct()
    {
        $this->initializeUuid();
        $this->date = new \DateTimeImmutable();
        $this->productAdded = 0;
        $this->details = [];
    }

    public function getStatus(): ?WeekScrapStatusEnum
    {
        return $this->status;
    }

    public function setStatus(WeekScrapStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getProductAdded(): int
    {
        return $this->productAdded;
    }

    public function setProductAdded(int $productAdded): static
    {
        $this->productAdded = $productAdded;

        return $this;
    }

    /**
     * @return array<string|null>
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @param array<string|null> $details
     */
    public function setDetails(array $details): static
    {
        $this->details = $details;

        return $this;
    }
}
