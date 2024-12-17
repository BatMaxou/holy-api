<?php

namespace App\Entity;

use App\Entity\Trait\UuidTrait;
use App\Repository\TierListRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TierListRepository::class)]
class TierList
{
    use UuidTrait;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
