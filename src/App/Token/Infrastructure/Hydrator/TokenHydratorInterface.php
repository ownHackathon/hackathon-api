<?php declare(strict_types=1);

namespace App\Token\Infrastructure\Hydrator;

use App\Token\Domain\TokenCollectionInterface;
use App\Token\Domain\TokenInterface;
use Core\Persistence\Hydrator\HydratorInterface;

interface TokenHydratorInterface extends HydratorInterface
{
    public function extract(TokenInterface $object): array;

    public function extractCollection(TokenCollectionInterface $collection): array;
}
