<?php

namespace App\Entity;

use App\Enum\WeekScrapStatusEnum;
use App\Repository\WeekScrapRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WeekScrapRepository::class)]
class WeekScrap
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private string $id;

    #[ORM\Column(enumType: WeekScrapStatusEnum::class)]
    private ?WeekScrapStatusEnum $status = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
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
}
