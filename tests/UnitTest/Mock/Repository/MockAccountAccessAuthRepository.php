<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Repository;

use ownHackathon\App\Repository\AccountAccessAuthRepository;
use ownHackathon\UnitTest\Mock\Table\MockAccountAccessAuthTable;

readonly class MockAccountAccessAuthRepository extends AccountAccessAuthRepository
{
    public function __construct()
    {
        parent::__construct(new MockAccountAccessAuthTable());
    }
}
