<?php declare(strict_types=1);

namespace UnitTest\Mock\Repository;

use App\Repository\AccountRepository;
use UnitTest\Mock\Table\MockAccountTable;

readonly class MockAccountRepository extends AccountRepository
{
    public function __construct()
    {
        parent::__construct(new MockAccountTable());
    }
}
