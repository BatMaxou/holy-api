<?php

namespace App\DTO;

use App\Enum\ProductRangeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScrapProductRangeDTO extends AbstractDTO
{
    /**
     * @param ScrapProductDTO[] $items
     */
    public function __construct(
        public ProductRangeEnum $productRange,
        public string $webSiteName,
        public array $items,
    ) {
    }

    public static function configureResolver(OptionsResolver $resolver): OptionsResolver
    {
        return $resolver
            ->setRequired('productRange')->setAllowedTypes('productRange', ProductRangeEnum::class)
            ->setRequired('webSiteName')->setAllowedTypes('webSiteName', 'string')
            ->setRequired('items')->setAllowedTypes('items', 'array')
        ;
    }
}
