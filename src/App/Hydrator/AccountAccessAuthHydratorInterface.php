<?php declare(strict_types=1);

namespace App\Hydrator;

use Core\Entity\Account\AccountAccessAuthCollectionInterface;
use Core\Entity\Account\AccountAccessAuthInterface;
use Core\Hydrator\HydratorInterface;

interface AccountAccessAuthHydratorInterface extends HydratorInterface
{
    public function extract(AccountAccessAuthInterface $object): array;

    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array;
}
