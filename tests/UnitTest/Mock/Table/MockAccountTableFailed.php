<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use App\Entity\Account\AccountCollection;
use App\Hydrator\AccountHydrator;
use App\Table\AccountTable;
use Core\Store\AccountStoreInterface;
use Core\Utils\UuidFactory;
use UnitTest\Mock\Database\MockQuery;

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
