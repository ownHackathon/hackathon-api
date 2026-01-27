<?php declare(strict_types=1);

namespace UnitTest\AppTest\Service;

use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Client\ClientIdentificationData;
use Exdrals\Identity\DTO\Token\JwtTokenConfig;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use DomainException;
use PHPUnit\Framework\TestCase;
use UnitTest\Mock\Constants\Token;

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

        $isValid = $service->isValid($token->refreshToken);

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
