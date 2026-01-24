<?php declare(strict_types=1);

namespace UnitTest\Mock\Service;

use Exdrals\Account\Identity\DTO\Client\ClientIdentification;
use Exdrals\Account\Identity\DTO\Token\JwtTokenConfig;
use Exdrals\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;

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
