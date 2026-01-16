<?php declare(strict_types=1);

namespace ownHackathon\App\Hydrator;

use ownHackathon\Core\Entity\Account\AccountActivationCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use ownHackathon\Core\Hydrator\HydratorInterface;

interface AccountActivationHydratorInterface extends HydratorInterface
{
    public function extract(AccountActivationInterface $object): array;

    public function extractCollection(AccountActivationCollectionInterface $collection): array;
}
