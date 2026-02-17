<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use Exdrals\Identity\Domain\AccountActivationCollectionInterface;
use Exdrals\Identity\Domain\AccountActivationInterface;

interface AccountActivationHydratorInterface extends HydratorInterface
{
    public function extract(AccountActivationInterface $object): array;

    public function extractCollection(AccountActivationCollectionInterface $collection): array;
}
