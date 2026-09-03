<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Hydrator;

use App\Account\Identity\Domain\AccountCollectionInterface;
use App\Account\Identity\Domain\AccountInterface;
use Core\Persistence\Hydrator\HydratorInterface;

interface AccountHydratorInterface extends HydratorInterface
{
    public function extract(AccountInterface $object): array;

    public function extractCollection(AccountCollectionInterface $collection): array;
}
