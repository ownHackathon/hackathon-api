<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Table;

use ownHackathon\App\Hydrator\AccountAccessAuthHydrator;
use ownHackathon\App\Table\AccountAccessAuthTable;
use ownHackathon\Core\Entity\Account\AccountAccessAuthCollectionInterface;
use ownHackathon\Core\Store\AccountAccessAuthStoreInterface;
use ownHackathon\UnitTest\Mock\Database\MockQuery;

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
