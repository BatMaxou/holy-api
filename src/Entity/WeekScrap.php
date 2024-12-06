<?php

namespace App\Entity;

use App\Enum\WeekScrapStatusEnum;
use App\Repository\WeekScrapRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WeekScrapRepository::class)]
class WeekScrap
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private string $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default' => '0000-00-00 00:00:00'])]
    private \DateTimeImmutable $date;

    #[ORM\Column(options: ['default' => 0])]
    private int $productAdded;

    #[ORM\Column(enumType: WeekScrapStatusEnum::class)]
    private ?WeekScrapStatusEnum $status = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->date = new \DateTimeImmutable();
        $this->productAdded = 0;
    }

    public function getId(): string
    {
        return $this->id;
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
}
