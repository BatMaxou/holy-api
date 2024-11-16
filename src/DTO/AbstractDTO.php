<?php

namespace App\DTO;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractDTO
{
    /**
     * @var array<class-string, OptionsResolver>
     */
    protected static array $resolvers = [];

    abstract public static function configureResolver(OptionsResolver $resolver): OptionsResolver;

    /**
     * @param mixed[] $data
     */
    public static function createFrom(array $data): static
    {
        $class = static::class;
        if (!isset(static::$resolvers[$class])) {
            $resolver = static::configureResolver(new OptionsResolver());
            static::$resolvers[$class] = $resolver;
        }

        return new static(...static::$resolvers[$class]->resolve($data)); // @phpstan-ignore-line
    }
}
