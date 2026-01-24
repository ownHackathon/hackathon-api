<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Hydrator\Account;

use Exdrals\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Account\Identity\Domain\AccountAccessAuthInterface;
use Shared\Infrastructure\Hydrator\HydratorInterface;

interface AccountAccessAuthHydratorInterface extends HydratorInterface
{
    public function extract(AccountAccessAuthInterface $object): array;

    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array;
}
