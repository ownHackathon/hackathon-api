<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Service;

use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\JwtTokenConfig;
use ownHackathon\App\Service\Token\RefreshTokenService;

readonly class MockRefreshTokenService extends RefreshTokenService
{
    public function __construct()
    {
        $config = new JwtTokenConfig('1', '1', 1, '1', '1');

        parent::__construct($config);
    }

    public function generate(ClientIdentification $clientIdentification): string
    {
        return 'test successfully';
    }
}
