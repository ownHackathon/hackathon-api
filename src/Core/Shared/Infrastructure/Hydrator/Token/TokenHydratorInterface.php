<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Infrastructure\Hydrator\Token;

use ownHackathon\Core\Shared\Domain\Token\TokenCollectionInterface;
use ownHackathon\Core\Shared\Domain\Token\TokenInterface;
use ownHackathon\Core\Shared\Infrastructure\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
