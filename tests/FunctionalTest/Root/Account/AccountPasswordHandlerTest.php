<?php declare(strict_types=1);

namespace FunctionalTest\Root\Account;

use App\Entity\Account\Account;
use App\Entity\Token\Token;
use Core\Enum\TokenType;
use Core\Repository\Account\AccountRepositoryInterface;
use Core\Repository\Token\TokenRepositoryInterface;
use Core\Type\Email;
use Core\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use FunctionalTest\AbstractFunctional;
use Laminas\Diactoros\ServerRequest;
use UnitTest\JsonRequestHelper;

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
            $this->account->id,
            TokenType::EMail,
            $this->uuid->uuid7(),
            new DateTimeImmutable()
        );
    }

    public function testChangePasswortHasStatusOk(): void
    {
        $this->tokenRepository->insert($this->token);

        $request = new ServerRequest(
            uri: '/api/account/password/' . $this->token->token->toString(),
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
            uri: '/api/account/password/' . $this->token->token->toString(),
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW]);
        $this->app->handle($request);

        $changedAccount = $this->accountRepository->findById($this->account->id);

        $this->assertNotSame($this->account->password, $changedAccount->password);
        $this->assertTrue(password_verify(self::PASSWORD_NEW, $changedAccount->password));
    }

    public function testChangedPasswordIsInvalid(): void
    {
        $this->tokenRepository->insert($this->token);

        $request = new ServerRequest(
            uri: '/api/account/password/' . $this->token->token->toString(),
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
            uri: '/api/account/password/' . $this->token->token->toString(),
            method: 'PATCH'
        );
        $request = $request->withParsedBody(['password' => self::PASSWORD_NEW]);
        $this->app->handle($request);

        $destroyedToken = $this->tokenRepository->findByToken($this->token->token->toString());

        $this->assertNull($destroyedToken);
    }
}
