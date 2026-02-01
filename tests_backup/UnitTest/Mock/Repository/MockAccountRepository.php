<?php declare(strict_types=1);

namespace UnitTest\Mock\Repository;

use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountRepository;
use UnitTest\Mock\Table\MockAccountTable;

readonly class MockAccountRepository extends AccountRepository
{
    public function __construct()
    {
        parent::__construct(new MockAccountTable());
    }
}
