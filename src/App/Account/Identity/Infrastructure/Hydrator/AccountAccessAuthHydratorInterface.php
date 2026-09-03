<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Hydrator;

use App\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use App\Account\Identity\Domain\AccountAccessAuthInterface;
use Core\Persistence\Hydrator\HydratorInterface;

interface AccountAccessAuthHydratorInterface extends HydratorInterface
{
    public function extract(AccountAccessAuthInterface $object): array;

    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array;
}
