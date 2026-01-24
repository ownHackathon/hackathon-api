<?php declare(strict_types=1);

namespace UnitTest\Mock\Table;

use Exdrals\Account\Identity\Domain\AccountCollection;
use Exdrals\Account\Identity\Infrastructure\Hydrator\Account\AccountHydrator;
use Exdrals\Account\Identity\Infrastructure\Persistence\Table\Account\AccountStoreInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Table\Account\AccountTable;
use Shared\Utils\UuidFactory;
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
