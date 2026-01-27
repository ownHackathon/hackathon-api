<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Hydrator\Account;

use Exdrals\Shared\Domain\Account\AccountCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Infrastructure\Hydrator\HydratorInterface;

interface AccountHydratorInterface extends HydratorInterface
{
    public function extract(AccountInterface $object): array;

    public function extractCollection(AccountCollectionInterface $collection): array;
}
