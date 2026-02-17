<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Trait;

/**
 * @see https://github.com/spatie/php-cloneable/
 */
trait CloneReadonlyClassWith
{
    public function with(mixed ...$properties): self
    {
        $properties += get_object_vars($this);

        return new self(...$properties);
    }
}
