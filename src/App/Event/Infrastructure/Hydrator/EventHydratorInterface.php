<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Infrastructure\Hydrator;

use ownHackathon\App\Event\Domain\EventCollectionInterface;
use ownHackathon\App\Event\Domain\EventInterface;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;

interface EventHydratorInterface extends HydratorInterface
{
    public function hydrate(array $data): EventInterface;

    public function hydrateCollection(array $data): EventCollectionInterface;

    public function extract(EventInterface $object): array;

    public function extractCollection(EventCollectionInterface $collection): array;
}
