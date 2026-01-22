<?php declare(strict_types=1);

namespace App\Hydrator\Account;

use Core\Entity\Account\AccountCollectionInterface;
use Core\Entity\Account\AccountInterface;
use Core\Hydrator\HydratorInterface;

interface AccountHydratorInterface extends HydratorInterface
{
    public function extract(AccountInterface $object): array;

    public function extractCollection(AccountCollectionInterface $collection): array;
}
