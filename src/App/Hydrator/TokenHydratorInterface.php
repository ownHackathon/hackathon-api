<?php declare(strict_types=1);

namespace App\Hydrator;

use Core\Entity\Token\TokenCollectionInterface;
use Core\Entity\Token\TokenInterface;
use Core\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
