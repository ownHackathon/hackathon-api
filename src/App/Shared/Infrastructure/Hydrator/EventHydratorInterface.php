<?php declare(strict_types=1);

namespace ownHackathon\Shared\Infrastructure\Hydrator;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use ownHackathon\Shared\Domain\Event\EventCollectionInterface;
use ownHackathon\Shared\Domain\Event\EventInterface;

interface EventHydratorInterface extends HydratorInterface
{
    public function hydrate(array $data): EventInterface;

    public function hydrateCollection(array $data): EventCollectionInterface;

    public function extract(EventInterface $object): array;

    public function extractCollection(EventCollectionInterface $collection): array;
}
