<?php declare(strict_types=1);

namespace App\Hydrator\Account;

use Core\Entity\Account\AccountActivationCollectionInterface;
use Core\Entity\Account\AccountActivationInterface;
use Core\Hydrator\HydratorInterface;

interface AccountActivationHydratorInterface extends HydratorInterface
{
    public function extract(AccountActivationInterface $object): array;

    public function extractCollection(AccountActivationCollectionInterface $collection): array;
}
