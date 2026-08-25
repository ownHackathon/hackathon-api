<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator;

use Exdrals\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use Exdrals\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Identity\Domain\AccountAccessAuthInterface;

interface AccountAccessAuthHydratorInterface extends HydratorInterface
{
    public function extract(AccountAccessAuthInterface $object): array;

    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array;
}
