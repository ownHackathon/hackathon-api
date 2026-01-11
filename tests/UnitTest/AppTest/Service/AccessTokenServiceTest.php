<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Service;

use DomainException;
use PHPUnit\Framework\TestCase;
use ownHackathon\App\DTO\JwtTokenConfig;
use ownHackathon\App\Service\Token\AccessTokenService;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\UnitTest\Mock\Constants\Token;
use ownHackathon\UnitTest\Mock\Entity\MockAccount;

class AccessTokenServiceTest extends TestCase
{
    private AccountInterface $account;

    protected function setUp(): void
    {
        $this->account = new MockAccount();
    }

    public function testGenerateValidAccessToken(): void
    {
        $config = Token::getTokenStruct();
        $jwtTokenConfig = JwtTokenConfig::createFromArray($config);

        $service = new AccessTokenService($jwtTokenConfig);

        $token = $service->generate($this->account->uuid);

        $isValid = $service->isValid($token);

        $this->assertTrue($isValid);
    }

    public function testGenerateValidAccessTokenFails(): void
    {
        $config = Token::getTokenStruct();
        $config['algorithmus'] = '';
        $jwtTokenConfig = JwtTokenConfig::createFromArray($config);

        $service = new AccessTokenService($jwtTokenConfig);

        $this->expectException(DomainException::class);

        $service->generate($this->account->uuid);
    }
}
