<?php declare(strict_types=1);

namespace FunctionalTest\Root\Account;

use DateTimeImmutable;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Exdrals\Mailing\Domain\EmailType;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use FunctionalTest\AbstractFunctional;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use UnitTest\JsonRequestHelper;
use UnitTest\Mock\Constants\Account;

use function password_hash;
use function rand;

use const PASSWORD_DEFAULT;

class AuthenticationHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;

    #[DataProvider('validAccountDataProvider')]
    public function testCanAuthenticate(string $email, string $password): void
    {
        $request = new ServerRequest(
            uri: '/api/account/authentication',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => $email, 'password' => $password])
            ->withAddedHeader('x-ident', (string)rand());
        $response = $this->app->handle($request);

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
    }

    #[DataProvider('invalidAuthenticateDataProvider')]
    public function testAuthenticateFailed(string $email, string $password): void
    {
        $request = new ServerRequest(
            uri: '/api/account/authentication',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => $email, 'password' => $password])
            ->withAddedHeader('x-ident', (string)rand());
        $response = $this->app->handle($request);

        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testNoDoubleSameLogins(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/authentication',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => 'owner@example.com', 'password' => 'owner123456'])
            ->withAddedHeader('x-ident', (string)rand());

        $response = $this->app->handle($request);
        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());

        $response = $this->app->handle($request);
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    public function testAccountIsAlreadyAuthenticated(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/authentication',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => 'owner@example.com', 'password' => 'owner123456'])
            ->withAddedHeader('x-ident', (string)rand())
            ->withAddedHeader('Authentication', 'Authentication');

        $response = $this->app->handle($request);
        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAccountIsAlreadyAuthorized(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/authentication',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => 'admin@example.com', 'password' => 'admin123456'])
            ->withAddedHeader('x-ident', (string)rand())
            ->withAddedHeader('Authorization', 'Authorization');

        $response = $this->app->handle($request);
        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testResponseHasValidAccessAndRefreshToken(): void
    {
        /** @var AccountRepositoryInterface $accountRepository */
        $accountRepository = $this->container->get(AccountRepositoryInterface::class);

        /** @var AccessTokenService $accessTokenService */
        $accessTokenService = $this->container->get(AccessTokenService::class);

        /** @var RefreshTokenService $refreshTokenService */
        $refreshTokenService = $this->container->get(RefreshTokenService::class);

        /** @var UuidFactoryInterface $uuid */
        $uuid = $this->container->get(UuidFactoryInterface::class);

        $account = new \Exdrals\Identity\Domain\Account(
            null,
            $uuid->uuid7(),
            'I see your Token',
            password_hash('I see your Token', PASSWORD_DEFAULT),
            new EmailType('iseeyourtoken@example.com'),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
        $accountRepository->insert($account);

        $request = new ServerRequest(
            uri: '/api/account/authentication',
            method: 'POST'
        );
        $request = $request->withParsedBody(
            ['email' => $account->email->toString(), 'password' => 'I see your Token']
        );

        $response = $this->app->handle($request);

        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'accessToken' => Assert::isType('string'),
            'refreshToken' => Assert::isType('string'),
        ]));

        $isAccessToken = $accessTokenService->isValid($content['accessToken']);
        $isRefreshToken = $refreshTokenService->isValid($content['refreshToken']);

        $this->assertTrue($isAccessToken);
        $this->assertTrue($isRefreshToken);
    }

    public static function validAccountDataProvider(): array
    {
        return [
            'Owner' => ['owner@example.com', 'owner123456'],
            'Administrator' => ['admin@example.com', 'admin123456'],
            'Moderator' => ['moderator@example.com', 'moderator'],
            'User' => ['user@example.com', 'user123456'],
            'Valid fixed Account Constant' => [Account::EMAIL, Account::PASSWORD_STRING,],
        ];
    }

    public static function invalidAuthenticateDataProvider(): array
    {
        return [
            'Empty Fields' => ['', ''],
            'Empty E-Mail' => ['', '123456'],
            'Empty Password' => ['account@example.com', ''],
            'No E-Mail' => ['no E-Mail', '123456'],
            'Invalid email prefixe' => ['abc..def@mail.com', '123456'],
            'Password too Short' => ['account@example.com', '123'],
            'Password too Long' => [
                'account@example.com',
                '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111'
                . '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111'
                . '111111111111111111111111111111111111111111111111111111',
            ],
            'Account with bad Password' => ['owner@example.com', '123456'],
            'SQL Injection Comment one' => ["owner@example.com'--", '123456'],
            'SQL Injection Comment two' => ["owner@example.com';", '123456'],
        ];
    }
}
