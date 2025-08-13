<?php declare(strict_types=1);

namespace ownHackathon\Core\Trait;

use ReflectionClass;

/**
 * @see https://github.com/spatie/php-cloneable/
 */
trait CloneReadonlyClassWith
{
    /** @phpstan-ignore-next-line */
    public function with(...$values): static
    {
        $refClass = new ReflectionClass(static::class);
        $clone = $refClass->newInstanceWithoutConstructor();

        foreach ($refClass->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $objectField = $property->getName();

            if (array_key_exists($objectField, $values)) {
                $objectValue = $values[$objectField];
            } elseif ($property->isInitialized($this)) {
                $objectValue = $property->getValue($this);
            } else {
                continue;
            }

            $declarationScope = $property->getDeclaringClass()->getName();
            if ($declarationScope === self::class) {
                $clone->$objectField = $objectValue;
            } else {
                (fn () => $this->$objectField = $objectValue)
                    ->bindTo($clone, $declarationScope)();
            }
        }

        return $clone;
    }
}
