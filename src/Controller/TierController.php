<?php

namespace App\Controller;

use App\Enum\HolyTierEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class TierController extends AbstractController
{
    #[Route('/tiers', name: 'tiers', methods: ['GET'])]
    public function tiers(): JsonResponse
    {
        return new JsonResponse(HolyTierEnum::getOrdered());
    }
}
