<?php declare(strict_types=1);

namespace ownHackathon\App\Hydrator;

use ownHackathon\Core\Entity\Token\TokenCollectionInterface;
use ownHackathon\Core\Entity\Token\TokenInterface;
use ownHackathon\Core\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
