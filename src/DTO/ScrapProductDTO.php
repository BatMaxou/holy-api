<?php

namespace App\DTO;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ScrapProductDTO extends AbstractDTO
{
    public function __construct(
        public string $name,
        public string $price,
        public string $imageUrl,
        public ?string $flavour = null
    ) {
    }

    public static function configureResolver(OptionsResolver $resolver): OptionsResolver
    {
        return $resolver
            ->setRequired('name')->setAllowedTypes('name', 'string')
            ->setRequired('price')->setAllowedTypes('price', 'int')
            ->setRequired('imageUrl')->setAllowedTypes('imageUrl', 'string')
            ->setDefined('flavour')->setAllowedTypes('flavour', ['string', 'null'])
        ;
    }
}
