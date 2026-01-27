<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Hydrator\Account;

use Exdrals\Shared\Domain\Account\AccountActivationCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountActivationInterface;
use Exdrals\Shared\Infrastructure\Hydrator\HydratorInterface;

interface AccountActivationHydratorInterface extends HydratorInterface
{
    public function extract(AccountActivationInterface $object): array;

    public function extractCollection(AccountActivationCollectionInterface $collection): array;
}
