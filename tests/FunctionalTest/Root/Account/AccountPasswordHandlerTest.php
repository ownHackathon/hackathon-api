<?php declare(strict_types=1);

namespace ownHackathon\FunctionalTest\Root\Account;

use DateTimeImmutable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\ServerRequest;
use ownHackathon\App\Entity\Account;
use ownHackathon\App\Entity\Token;
use ownHackathon\App\Enum\TokenType;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Repository\TokenRepositoryInterface;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\UuidFactoryInterface;
use ownHackathon\FunctionalTest\AbstractFunctional;
use ownHackathon\UnitTest\JsonRequestHelper;

use function password_verify;

class AccountPasswordHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;

    private AccountRepositoryInterface $accountRepository;
    private TokenRepositoryInterface $tokenRepository;
    private UuidFactoryInterface $uuid;
    private Account $account;
    private Token $token;

    private const string PASSWORD_NEW = 'neues Passwort';
    private const string PASSWORD_NEW_INVALID = '1';

    public function setUp(): void
    {
        parent::setUp();
        $this->accountRepository = $this->container->get(AccountRepositoryInterface::class);
        $this->tokenRepository = $this->container->get(TokenRepositoryInterface::class);
        $this->uuid = $this->container->get(UuidFactoryInterface::class);

        $this->account = $this->accountRepository->findByEmail(new Email('user@example.com'));
        $this->token = new Token(
            null,
            $this->account->getId(),
            TokenType::EMail,
            $this->uuid->uuid7(),
            new DateTimeImmutable()
        );
    }

    public function testChangePasswortHasStatusOk(): void
    {
        $this->tokenRepository->insert($this->token);

        $request = new ServerRequest(
            uri: '/api/account/password/' . $this->token->getToken()->getHex()->toString(),
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW]);
        $response = $this->app->handle($request);

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
    }

    public function testChangedPasswordIsValid(): void
    {
        $this->tokenRepository->insert($this->token);

        $request = new ServerRequest(
            uri: '/api/account/password/' . $this->token->getToken()->getHex()->toString(),
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW]);
        $this->app->handle($request);

        $changedAccount = $this->accountRepository->findById($this->account->getId());

        $this->assertNotSame($this->account->getPasswordHash(), $changedAccount->getPasswordHash());
        $this->assertTrue(password_verify(self::PASSWORD_NEW, $changedAccount->getPasswordHash()));
    }

    public function testChangedPasswordIsInvalid(): void
    {
        $this->tokenRepository->insert($this->token);

        $request = new ServerRequest(
            uri: '/api/account/password/' . $this->token->getToken()->getHex()->toString(),
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW_INVALID]);
        $response = $this->app->handle($request);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    public function testTokenIsInvalid(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/password/InvalidToken',
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW]);
        $response = $this->app->handle($request);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    public function testTokenIsMissed(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/password/',
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW]);
        $response = $this->app->handle($request);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    public function testTokenWasDestroyed(): void
    {
        $this->tokenRepository->insert($this->token);

        $request = new ServerRequest(
            uri: '/api/account/password/' . $this->token->getToken()->getHex()->toString(),
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW]);
        $this->app->handle($request);

        $destroyedToken = $this->tokenRepository->findByToken($this->token->getToken()->getHex()->toString());

        $this->assertNull($destroyedToken);
    }
}
