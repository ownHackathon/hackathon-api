<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\Client\ClientIdentification;
use ownHackathon\App\DTO\Client\ClientIdentificationData;
use ownHackathon\App\DTO\Token\RefreshToken;
use ownHackathon\App\Hydrator\AccountHydrator;
use ownHackathon\App\Hydrator\AccountHydratorInterface;
use ownHackathon\App\Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Exception\HttpDuplicateEntryException;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\Core\Utils\UuidFactory;
use ownHackathon\UnitTest\Mock\Constants\Account;
use ownHackathon\UnitTest\Mock\Repository\MockAccountAccessAuthRepository;

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
