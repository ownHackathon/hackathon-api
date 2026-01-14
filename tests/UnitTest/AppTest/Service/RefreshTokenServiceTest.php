<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Service;

use DomainException;
use ownHackathon\App\DTO\Client\ClientIdentification;
use ownHackathon\App\DTO\Client\ClientIdentificationData;
use ownHackathon\App\DTO\Token\JwtTokenConfig;
use ownHackathon\App\Service\Token\RefreshTokenService;
use ownHackathon\UnitTest\Mock\Constants\Token;
use PHPUnit\Framework\TestCase;

class RefreshTokenServiceTest extends TestCase
{
    private ClientIdentification $client;

    protected function setUp(): void
    {
        $this->client = ClientIdentification::create(
            ClientIdentificationData::create(null, 'default'),
            '1'
        );
    }

    public function testGenerateValidRefreshToken(): void
    {
        $config = Token::getTokenStruct();
        $jwtTokenConfig = JwtTokenConfig::createFromArray($config);

        $service = new RefreshTokenService($jwtTokenConfig);

        $token = $service->generate($this->client);

        $isValid = $service->isValid($token);

        $this->assertTrue($isValid);
    }

    public function testGenerateValidRefreshTokenFails(): void
    {
        $config = Token::getTokenStruct();
        $config['algorithmus'] = '';
        $jwtTokenConfig = JwtTokenConfig::createFromArray($config);

        $service = new RefreshTokenService($jwtTokenConfig);

        $this->expectException(DomainException::class);

        $service->generate($this->client);
    }
}
