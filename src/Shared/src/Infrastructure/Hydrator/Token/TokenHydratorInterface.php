<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Hydrator\Token;

use Exdrals\Shared\Domain\Token\TokenCollectionInterface;
use Exdrals\Shared\Domain\Token\TokenInterface;
use Exdrals\Shared\Infrastructure\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
