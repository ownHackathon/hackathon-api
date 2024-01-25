<?php declare(strict_types=1);

namespace App\System\Trait;

trait CloneReadonlyClassWith
{
    public function with(...$properties): static
    {
        $properties += (array)$this;

        return new static(...$properties);
    }
}
