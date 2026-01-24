<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Hydrator\Token;

use Exdrals\Account\Identity\Domain\TokenCollectionInterface;
use Exdrals\Account\Identity\Domain\TokenInterface;
use Shared\Infrastructure\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
