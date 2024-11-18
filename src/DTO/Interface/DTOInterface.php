<?php

namespace App\DTO\Interface;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface DTOInterface
{
    public static function configureResolver(OptionsResolver $resolver): OptionsResolver;

    /**
     * @param mixed[] $data
     */
    public static function createFrom(array $data): static;
}
