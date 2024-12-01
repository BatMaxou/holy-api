<?php

namespace App\DTO;

use App\DTO\Interface\DTOInterface;
use App\Enum\ProductRangeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScrapProductDTO extends AbstractDTO implements DTOInterface
{
    public string $imageUrl;

    public function __construct(
        public string $name,
        public ProductRangeEnum $productRange,
        public string $price,
        public string $originalImageUrl,
        public ?string $flavour = null
    ) {
        $this->imageUrl = $originalImageUrl;
    }

    public static function configureResolver(OptionsResolver $resolver): OptionsResolver
    {
        return $resolver
            ->setRequired('name')->setAllowedTypes('name', 'string')
            ->setRequired('productRange')->setAllowedTypes('productRange', ProductRangeEnum::class)
            ->setRequired('price')->setAllowedTypes('price', 'int')
            ->setRequired('originalImageUrl')->setAllowedTypes('originalImageUrl', 'string')
            ->setDefined('flavour')->setAllowedTypes('flavour', ['string', 'null'])
        ;
    }
}
