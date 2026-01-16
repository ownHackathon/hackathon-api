<?php declare(strict_types=1);

namespace Core\Trait;

trait CloneReadonlyClassWith
{
    /** @phpstan-ignore-next-line */
    public function with(...$properties): static
    {
        $properties += (array)$this;

        return new static(...$properties);
    }
}
