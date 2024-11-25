<?php

namespace App\Entity;

use App\Repository\TierListRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TierListRepository::class)]
class TierList
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private string $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): string
    {
        return $this->id;
    }
}
