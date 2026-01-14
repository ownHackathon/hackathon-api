<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Table;

use ownHackathon\App\Entity\Account\AccountCollection;
use ownHackathon\App\Hydrator\AccountHydrator;
use ownHackathon\App\Table\AccountTable;
use ownHackathon\Core\Store\AccountStoreInterface;
use ownHackathon\Core\Utils\UuidFactory;
use ownHackathon\UnitTest\Mock\Database\MockQuery;

class MockAccountTableFailed extends AccountTable implements AccountStoreInterface
{
    public function __construct()
    {
        parent::__construct(
            new MockQuery(),
            new AccountHydrator(new UuidFactory()),
        );
    }

    public function findAll(): AccountCollection
    {
        return $this->hydrator->hydrateCollection([]);
    }
}
