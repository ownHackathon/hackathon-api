<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use Exdrals\Identity\Domain\AccountCollection;
use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountHydrator;
use Exdrals\Identity\Infrastructure\Persistence\Table\Account\AccountTable;
use Exdrals\Shared\Infrastructure\Persistence\Store\Account\AccountStoreInterface;
use Exdrals\Shared\Utils\UuidFactory;
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
