<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator\Token;

use Exdrals\Identity\Domain\TokenCollectionInterface;
use Exdrals\Identity\Domain\TokenInterface;
use Exdrals\Shared\Infrastructure\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
