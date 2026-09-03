<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Hydrator;

use App\Account\Identity\Domain\AccountActivationCollectionInterface;
use App\Account\Identity\Domain\AccountActivationInterface;
use Core\Persistence\Hydrator\HydratorInterface;

interface AccountActivationHydratorInterface extends HydratorInterface
{
    public function extract(AccountActivationInterface $object): array;

    public function extractCollection(AccountActivationCollectionInterface $collection): array;
}
