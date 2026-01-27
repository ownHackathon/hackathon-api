<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Hydrator\Account;

use Exdrals\Shared\Domain\Account\AccountAccessAuthCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountAccessAuthInterface;
use Exdrals\Shared\Infrastructure\Hydrator\HydratorInterface;

interface AccountAccessAuthHydratorInterface extends HydratorInterface
{
    public function extract(AccountAccessAuthInterface $object): array;

    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array;
}
