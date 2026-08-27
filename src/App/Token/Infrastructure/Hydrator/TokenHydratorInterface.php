<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Infrastructure\Hydrator;

use ownHackathon\App\Token\Domain\TokenCollectionInterface;
use ownHackathon\App\Token\Domain\TokenInterface;
use ownHackathon\Core\Shared\Infrastructure\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
