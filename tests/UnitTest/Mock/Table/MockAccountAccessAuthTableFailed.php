<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountAccessAuthHydrator;
use Exdrals\Identity\Infrastructure\Persistence\Table\Account\AccountAccessAuthTable;
use Exdrals\Shared\Domain\Account\AccountAccessAuthCollectionInterface;
use Exdrals\Shared\Infrastructure\Persistence\Store\Account\AccountAccessAuthStoreInterface;
use UnitTest\Mock\Database\MockQuery;

class MockAccountAccessAuthTableFailed extends AccountAccessAuthTable implements AccountAccessAuthStoreInterface
{
    public function __construct()
    {
        parent::__construct(
            new MockQuery(),
            new AccountAccessAuthHydrator(),
        );
    }

    public function findAll(): AccountAccessAuthCollectionInterface
    {
        return $this->hydrator->hydrateCollection([]);
    }
}
