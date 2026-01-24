<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Hydrator\Account;

use Exdrals\Account\Identity\Domain\AccountActivationCollectionInterface;
use Exdrals\Account\Identity\Domain\AccountActivationInterface;
use Shared\Infrastructure\Hydrator\HydratorInterface;

interface AccountActivationHydratorInterface extends HydratorInterface
{
    public function extract(AccountActivationInterface $object): array;

    public function extractCollection(AccountActivationCollectionInterface $collection): array;
}
