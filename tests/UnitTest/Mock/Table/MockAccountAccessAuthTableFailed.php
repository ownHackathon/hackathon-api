<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use Exdrals\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Account\Identity\Infrastructure\Hydrator\Account\AccountAccessAuthHydrator;
use Exdrals\Account\Identity\Infrastructure\Persistence\Table\Account\AccountAccessAuthStoreInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Table\Account\AccountAccessAuthTable;
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
