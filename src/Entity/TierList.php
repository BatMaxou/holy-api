<?php

namespace App\Entity;

use App\Entity\Trait\UuidTrait;
use App\Repository\TierListRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TierListRepository::class)]
class TierList
{
    use UuidTrait;
}
