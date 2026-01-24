<?php declare(strict_types=1);

namespace FunctionalTest\Root\Account;

use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use FunctionalTest\AbstractFunctional;
use Laminas\Diactoros\ServerRequest;
use Ramsey\Uuid\UuidInterface;
use UnitTest\JsonRequestHelper;

class AccountPasswordForgottenHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;
    use InvalidEMailAddressProviderTrait;

    private const string EMAIL_VALID = 'valid@example.com';
    private const string EMAIL_INVALID = 'invalid@example.com';

    public function testCreateTokenForPasswordChange(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/password/forgotten',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => self::EMAIL_VALID]);
        $response = $this->app->handle($request);

        /** @var AccountRepositoryInterface $accountRepository */
        $accountRepository = $this->container->get(AccountRepositoryInterface::class);
        $account = $accountRepository->findByEmail(new EmailType(self::EMAIL_VALID));

        /** @var TokenRepositoryInterface $tokenRepository */
        $tokenRepository = $this->container->get(TokenRepositoryInterface::class);

        $token = $tokenRepository->findByAccountId($account->id);

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
        $this->assertArrayHasKey(0, $token);
        $this->assertInstanceOf(UuidInterface::class, $token[0]->token);
    }

    public function testDontCreateTokenForPasswordChange(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/password/forgotten',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => self::EMAIL_INVALID]);
        $response = $this->app->handle($request);

        /** @var AccountRepositoryInterface $accountRepository */
        $accountRepository = $this->container->get(AccountRepositoryInterface::class);
        $account = $accountRepository->findByEmail(new EmailType(self::EMAIL_INVALID));

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
        $this->assertNull($account);
    }
}
