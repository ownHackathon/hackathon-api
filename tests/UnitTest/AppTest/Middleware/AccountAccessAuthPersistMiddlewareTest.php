<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use App\DTO\Client\ClientIdentification;
use App\DTO\Client\ClientIdentificationData;
use App\DTO\Token\RefreshToken;
use App\Hydrator\Account\AccountHydrator;
use App\Hydrator\Account\AccountHydratorInterface;
use App\Hydrator\Account\WorkspaceHydrator;
use App\Hydrator\Account\WorkspaceHydratorInterface;
use App\Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware;
use Core\Entity\Account\AccountInterface;
use Core\Exception\HttpDuplicateEntryException;
use Core\Exception\HttpUnauthorizedException;
use Core\Repository\Account\AccountAccessAuthRepositoryInterface;
use Core\Utils\UuidFactory;
use Laminas\Diactoros\Response\JsonResponse;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\Repository\MockAccountAccessAuthRepository;

class AccountAccessAuthPersistMiddlewareTest extends AbstractTestMiddleware
{
    private AccountAccessAuthRepositoryInterface $repository;
    private AccountHydratorInterface $hydrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new MockAccountAccessAuthRepository();
        $this->hydrator = new AccountHydrator(new UuidFactory());
    }

    public function testCanPersistAccountAccessAuth(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository);
        $account = $this->hydrator->hydrate(Account::VALID_DATA);
        $clientData = ClientIdentificationData::create('1', 'default');
        $clientIdent = ClientIdentification::create($clientData, '1234');
        $refreshToken = RefreshToken::fromString('1234');

        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, $account)
            ->withAttribute(ClientIdentification::class, $clientIdent)
            ->withAttribute(RefreshToken::class, $refreshToken);

        $response = $middleware->process($request, $this->handler);

        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testFindMissingAccountEntity(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository);

        $clientData = ClientIdentificationData::create('1', 'default');
        $clientIdent = ClientIdentification::create($clientData, '1234');
        $refreshToken = RefreshToken::fromString('1234');

        $request = $this->request->withAttribute(ClientIdentification::class, $clientIdent)
            ->withAttribute(RefreshToken::class, $refreshToken);

        $this->expectException(HttpUnauthorizedException::class);
        $middleware->process($request, $this->handler);
    }

    public function testFindMissingClientIdentification(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository);

        $account = $this->hydrator->hydrate(Account::VALID_DATA);
        $refreshToken = RefreshToken::fromString('1234');

        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, $account)
            ->withAttribute(RefreshToken::class, $refreshToken);

        $this->expectException(HttpUnauthorizedException::class);
        $middleware->process($request, $this->handler);
    }

    public function testFindMissingRefreshToken(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository);

        $account = $this->hydrator->hydrate(Account::VALID_DATA);
        $clientData = ClientIdentificationData::create('1', 'default');
        $clientIdent = ClientIdentification::create($clientData, '1234');

        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, $account)
            ->withAttribute(ClientIdentification::class, $clientIdent);

        $this->expectException(HttpUnauthorizedException::class);
        $middleware->process($request, $this->handler);
    }

    public function testAccountAccessAuthHasDuplicat(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository);
        $account = $this->hydrator->hydrate(Account::INVALID_DATA);
        $clientData = ClientIdentificationData::create('1', 'default');
        $clientIdent = ClientIdentification::create($clientData, '1234');
        $refreshToken = RefreshToken::fromString('1234');

        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, $account)
            ->withAttribute(ClientIdentification::class, $clientIdent)
            ->withAttribute(RefreshToken::class, $refreshToken);

        $this->expectException(HttpDuplicateEntryException::class);
        $middleware->process($request, $this->handler);
    }
}
