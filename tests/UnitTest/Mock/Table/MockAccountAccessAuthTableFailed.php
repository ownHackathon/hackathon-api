<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use App\Hydrator\AccountAccessAuthHydrator;
use App\Table\AccountAccessAuthTable;
use Core\Entity\Account\AccountAccessAuthCollectionInterface;
use Core\Store\AccountAccessAuthStoreInterface;
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
