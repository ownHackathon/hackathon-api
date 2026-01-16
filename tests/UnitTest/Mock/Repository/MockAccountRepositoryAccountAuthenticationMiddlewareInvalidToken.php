<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Repository;

use ownHackathon\App\Repository\AccountRepository;
use ownHackathon\UnitTest\Mock\Table\MockAccountTableAccountAuthenticationMiddlewareInvalidToken;

readonly class MockAccountRepositoryAccountAuthenticationMiddlewareInvalidToken extends AccountRepository
{
    public function __construct()
    {
        parent::__construct(new MockAccountTableAccountAuthenticationMiddlewareInvalidToken());
    }
}
