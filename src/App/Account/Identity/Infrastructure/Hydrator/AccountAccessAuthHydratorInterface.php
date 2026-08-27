<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Hydrator;

use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthInterface;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;

interface AccountAccessAuthHydratorInterface extends HydratorInterface
{
    public function extract(AccountAccessAuthInterface $object): array;

    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array;
}
