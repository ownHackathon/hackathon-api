<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Hydrator;

interface HydratorInterface
{
    public function hydrate(array $data): mixed;

    public function hydrateCollection(array $data): mixed;
}
