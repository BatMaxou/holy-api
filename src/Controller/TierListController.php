<?php

namespace App\Controller;

use App\Enum\HolyTierEnum;
use App\Repository\RankedProductRepository;
use App\Repository\TierListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class TierListController extends AbstractController
{
    #[Route('/tiers', name: 'tiers')]
    public function tiers(): JsonResponse
    {
        return new JsonResponse(HolyTierEnum::getOrdered());
    }

    #[Route('/tier-list', name: 'tier_list')]
    public function tierList(
        TierListRepository $tierListRepository,
        RankedProductRepository $rankedProductRepository,
    ): JsonResponse {
        $tierList = $tierListRepository->findOnlyOne();
        if (null === $tierList) {
            throw new \Exception('Tier List not found');
        }

        $linkedRankedProducts = $rankedProductRepository->findByTierList($tierList);

        return new JsonResponse([
            'tierListId' => $tierList->getId(),
            'products' => array_map(fn ($linkedRankedProduct) => [
                'id' => $linkedRankedProduct->getProduct()->getId(),
                'tier' => $linkedRankedProduct->getTier(),
                'imageUrl' => $linkedRankedProduct->getProduct()->getImageUrl(),
            ], $linkedRankedProducts),
        ]);
    }

    #[Route('/tier-list/products/{id}', name: 'tier_list_products', methods: ['PATCH'])]
    public function updateTierListProduct(
        string $id,
        Request $request,
        RankedProductRepository $rankedProductRepository,
    ): JsonResponse {
        $rankedProduct = $rankedProductRepository->find($id);
        if (null === $rankedProduct) {
            throw new \Exception('Ranked Product not found');
        }

        $data = json_decode($request->getContent(), true);

        if (!is_array($data) || !isset($data['tier']) || !is_string($data['tier'])) {
            throw new \Exception('Invalid tier');
        }

        $tier = HolyTierEnum::tryFrom($data['tier']);
        if (null === $tier) {
            throw new \Exception('Invalid tier');
        }

        $rankedProduct->setTier($tier);
        $rankedProductRepository->save();

        return new JsonResponse([
            'id' => $rankedProduct->getId(),
            'tier' => $rankedProduct->getTier(),
            'imageUrl' => $rankedProduct->getProduct()->getImageUrl(),
        ]);
    }
}
