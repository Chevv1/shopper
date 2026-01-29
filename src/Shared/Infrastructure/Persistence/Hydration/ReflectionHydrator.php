<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Hydration;

use ReflectionClass;
use ReflectionException;

trait ReflectionHydrator
{
    /**
     * * @template T of object
     * @param class-string<T> $className
     * @param array<string, mixed> $data
     * @return T
     * @throws ReflectionException
     */
    protected static function hydrate(string $className, array $data): object
    {
        $reflection = new ReflectionClass(objectOrClass: $className);
        $instance = $reflection->newInstanceWithoutConstructor();

        foreach ($data as $propertyName => $value) {
            if ($reflection->hasProperty(name: $propertyName)) {
                $reflection
                    ->getProperty(name: $propertyName)
                    ->setValue(objectOrValue: $instance, value: $value);
            }
        }

        return $instance;
    }
}
