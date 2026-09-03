<?php declare(strict_types=1);

namespace App\Event\Infrastructure\Hydrator;

use App\Event\Domain\EventCollectionInterface;
use App\Event\Domain\EventInterface;
use Core\Persistence\Hydrator\HydratorInterface;

interface EventHydratorInterface extends HydratorInterface
{
    public function hydrate(array $data): EventInterface;

    public function hydrateCollection(array $data): EventCollectionInterface;

    public function extract(EventInterface $object): array;

    public function extractCollection(EventCollectionInterface $collection): array;
}
