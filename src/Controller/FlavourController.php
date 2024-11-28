<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class FlavourController extends AbstractController
{
    #[Route('/flavours', name: 'flavours')]
    public function flavours(ProductRepository $productRepository): JsonResponse
    {
        $products = array_map(fn ($flavour) => [
            'name' => $flavour->getName(),
            'flavour' => $flavour->getFlavour(),
            'imageUrl' => $flavour->getImageUrl(),
        ], $productRepository->findAllFlavour());

        return new JsonResponse($products);
    }
}
