<?php declare(strict_types=1);

namespace ownHackathon\App\Shared\Infrastructure\Hydrator;

use ownHackathon\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use ownHackathon\App\Shared\Domain\Event\EventCollectionInterface;
use ownHackathon\App\Shared\Domain\Event\EventInterface;

interface EventHydratorInterface extends HydratorInterface
{
    public function hydrate(array $data): EventInterface;

    public function hydrateCollection(array $data): EventCollectionInterface;

    public function extract(EventInterface $object): array;

    public function extractCollection(EventCollectionInterface $collection): array;
}
