<?php

namespace App\Controller;

use App\Entity\RankedProduct;
use App\Enum\HolyTierEnum;
use App\Repository\RankedProductRepository;
use App\Repository\TierListRepository;
use App\Service\Sorting\RankedProductOrderModifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class TierListController extends AbstractController
{
    #[Route('/tier-list/products', name: 'tier_list', methods: ['GET'])]
    public function tierList(
        TierListRepository $tierListRepository,
        RankedProductRepository $rankedProductRepository,
    ): JsonResponse {
        $user = $this->getUser();
        $tierList = $tierListRepository->findOneBy(['user' => $user]);
        if (null === $user || null === $tierList) {
            throw new \Exception('Tier List not found');
        }

        $linkedRankedProducts = $rankedProductRepository->findByTierList($tierList);

        return new JsonResponse(array_map(fn ($linkedRankedProduct) => [
            'id' => $linkedRankedProduct->getId(),
            'tier' => $linkedRankedProduct->getTier(),
            'order' => $linkedRankedProduct->getOrderNumber(),
            'imageUrl' => $linkedRankedProduct->getProduct()->getImageUrl(),
        ], $linkedRankedProducts));
    }

    #[Route('/tier-list/products/{id}', name: 'tier_list_products', methods: ['PATCH'])]
    public function updateTierListProduct(
        string $id,
        Request $request,
        RankedProductRepository $rankedProductRepository,
        RankedProductOrderModifier $rankedProductOrderModifier,
    ): JsonResponse {
        $user = $this->getUser();
        if (null === $user) {
            throw new \LogicException('Auth not configured');
        }

        $rankedProduct = $rankedProductRepository->find($id);
        if (!$rankedProduct instanceof RankedProduct || $rankedProduct->getTierList()->getUser() !== $user) {
            throw new \Exception('Ranked Product not found');
        }

        $data = json_decode($request->getContent(), false);
        if (!is_object($data)) {
            throw new \Exception('Invalid data');
        }

        if (!isset($data->tier) || !is_string($data->tier)) {
            throw new \Exception('Invalid tier');
        }

        if (!isset($data->order) || !is_int($data->order)) {
            throw new \Exception('Invalid order');
        }

        $tier = HolyTierEnum::tryFrom($data->tier);
        if (null === $tier) {
            throw new \Exception('Invalid tier');
        }

        $order = $data->order;
        if ($order <= 0) {
            throw new \Exception('Invalid order');
        }

        $oldTier = $rankedProduct->getTier();
        $oldOrder = $rankedProduct->getOrderNumber();

        if ($oldTier === $tier && $oldOrder === $order) {
            return new JsonResponse([
                'id' => $rankedProduct->getId(),
                'tier' => $rankedProduct->getTier(),
                'order' => $rankedProduct->getOrderNumber(),
                'imageUrl' => $rankedProduct->getProduct()->getImageUrl(),
            ]);
        }

        if ($oldTier === $tier) {
            $rankedProductOrderModifier->modifyBetween($rankedProduct->getTierList(), $tier, $rankedProduct, $oldOrder, $order);
        } else {
            if (null !== $oldOrder) {
                $rankedProductOrderModifier->pop($rankedProduct->getTierList(), $oldTier, $rankedProduct, $oldOrder);
            }
            $rankedProductOrderModifier->insert($rankedProduct->getTierList(), $tier, $rankedProduct, $order);
        }

        $rankedProduct->setTier($tier);
        $rankedProduct->setOrderNumber($order);
        $rankedProductRepository->save();

        return new JsonResponse([
            'id' => $rankedProduct->getId(),
            'tier' => $rankedProduct->getTier(),
            'order' => $rankedProduct->getOrderNumber(),
            'imageUrl' => $rankedProduct->getProduct()->getImageUrl(),
        ]);
    }
}
