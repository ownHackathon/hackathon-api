<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Hydrator;

use ownHackathon\App\Account\Identity\Domain\AccountActivationCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountActivationInterface;
use ownHackathon\Core\Shared\Infrastructure\Hydrator\HydratorInterface;

interface AccountActivationHydratorInterface extends HydratorInterface
{
    public function extract(AccountActivationInterface $object): array;

    public function extractCollection(AccountActivationCollectionInterface $collection): array;
}
