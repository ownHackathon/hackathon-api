<?php declare(strict_types=1);

namespace UnitTest\Mock\Repository;

use App\Repository\AccountRepository;
use UnitTest\Mock\Table\MockAccountTableAccountAuthenticationMiddlewareInvalidToken;

readonly class MockAccountRepositoryAccountAuthenticationMiddlewareInvalidToken extends AccountRepository
{
    public function __construct()
    {
        parent::__construct(new MockAccountTableAccountAuthenticationMiddlewareInvalidToken());
    }
}
