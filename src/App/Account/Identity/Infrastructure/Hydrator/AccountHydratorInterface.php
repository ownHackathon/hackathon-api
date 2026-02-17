<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use Exdrals\Identity\Domain\AccountCollectionInterface;
use Exdrals\Identity\Domain\AccountInterface;

interface AccountHydratorInterface extends HydratorInterface
{
    public function extract(AccountInterface $object): array;

    public function extractCollection(AccountCollectionInterface $collection): array;
}
