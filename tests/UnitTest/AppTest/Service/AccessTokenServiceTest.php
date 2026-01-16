<?php declare(strict_types=1);

namespace UnitTest\AppTest\Service;

use DomainException;
use App\DTO\Token\JwtTokenConfig;
use App\Service\Token\AccessTokenService;
use Core\Entity\Account\AccountInterface;
use UnitTest\Mock\Constants\Token;
use UnitTest\Mock\Entity\MockAccount;
use PHPUnit\Framework\TestCase;

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
