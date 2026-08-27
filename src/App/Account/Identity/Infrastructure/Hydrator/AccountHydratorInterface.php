<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Hydrator;

use ownHackathon\App\Account\Identity\Domain\AccountCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;

interface AccountHydratorInterface extends HydratorInterface
{
    public function extract(AccountInterface $object): array;

    public function extractCollection(AccountCollectionInterface $collection): array;
}
