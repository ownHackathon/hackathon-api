<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Hydrator\Account;

use Exdrals\Account\Identity\Domain\AccountCollectionInterface;
use Exdrals\Account\Identity\Domain\AccountInterface;
use Shared\Infrastructure\Hydrator\HydratorInterface;

interface AccountHydratorInterface extends HydratorInterface
{
    public function extract(AccountInterface $object): array;

    public function extractCollection(AccountCollectionInterface $collection): array;
}
