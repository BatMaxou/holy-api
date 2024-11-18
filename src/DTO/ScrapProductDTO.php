<?php

namespace App\DTO;

use App\DTO\Interface\DTOInterface;
use App\Enum\ProductRange;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScrapProductDTO extends AbstractDTO implements DTOInterface
{
    public function __construct(
        public string $name,
        public ProductRange $productRange,
        public string $price,
        public string $imageUrl,
        public ?string $flavour = null
    ) {
    }

    public static function configureResolver(OptionsResolver $resolver): OptionsResolver
    {
        return $resolver
            ->setRequired('name')->setAllowedTypes('name', 'string')
            ->setRequired('productRange')->setAllowedTypes('productRange', ProductRange::class)
            ->setRequired('price')->setAllowedTypes('price', 'int')
            ->setRequired('imageUrl')->setAllowedTypes('imageUrl', 'string')
            ->setDefined('flavour')->setAllowedTypes('flavour', ['string', 'null'])
        ;
    }
}
