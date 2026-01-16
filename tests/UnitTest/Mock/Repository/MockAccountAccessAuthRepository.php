<?php declare(strict_types=1);

namespace UnitTest\Mock\Repository;

use App\Repository\AccountAccessAuthRepository;
use UnitTest\Mock\Table\MockAccountAccessAuthTable;

readonly class MockAccountAccessAuthRepository extends AccountAccessAuthRepository
{
    public function __construct()
    {
        parent::__construct(new MockAccountAccessAuthTable());
    }
}
