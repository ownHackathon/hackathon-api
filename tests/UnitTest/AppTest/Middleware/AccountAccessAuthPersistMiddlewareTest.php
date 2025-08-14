<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\Core\Message\ResponseMessage;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\ClientIdentificationData;
use ownHackathon\App\DTO\RefreshToken;
use ownHackathon\App\Hydrator\AccountHydrator;
use ownHackathon\App\Hydrator\AccountHydratorInterface;
use ownHackathon\App\Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\Core\Utils\UuidFactory;
use ownHackathon\FunctionalTest\Mock\NullLogger;
use ownHackathon\UnitTest\Mock\Constants\Account;
use ownHackathon\UnitTest\Mock\Repository\MockAccountAccessAuthRepository;

class AccountAccessAuthPersistMiddlewareTest extends AbstractTestMiddleware
{
    private AccountAccessAuthRepositoryInterface $repository;
    private AccountHydratorInterface $hydrator;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new MockAccountAccessAuthRepository();
        $this->hydrator = new AccountHydrator(new UuidFactory());
        $this->logger = new NullLogger();
    }

    public function testCanPersistAccountAccessAuth(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository, $this->logger);
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
        $middleware = new PersistAuthenticationMiddleware($this->repository, $this->logger);

        $clientData = ClientIdentificationData::create('1', 'default');
        $clientIdent = ClientIdentification::create($clientData, '1234');
        $refreshToken = RefreshToken::fromString('1234');

        $request = $this->request->withAttribute(ClientIdentification::class, $clientIdent)
            ->withAttribute(RefreshToken::class, $refreshToken);

        $response = $middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', StatusCodeInterface::STATUS_UNAUTHORIZED);
        $this->assertJsonValueEquals($json, '$.message', ResponseMessage::DATA_INVALID);
    }

    public function testFindMissingClientIdentification(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository, $this->logger);

        $account = $this->hydrator->hydrate(Account::VALID_DATA);
        $refreshToken = RefreshToken::fromString('1234');

        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, $account)
            ->withAttribute(RefreshToken::class, $refreshToken);

        $response = $middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', StatusCodeInterface::STATUS_UNAUTHORIZED);
        $this->assertJsonValueEquals($json, '$.message', ResponseMessage::DATA_INVALID);
    }

    public function testFindMissingRefreshToken(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository, $this->logger);

        $account = $this->hydrator->hydrate(Account::VALID_DATA);
        $clientData = ClientIdentificationData::create('1', 'default');
        $clientIdent = ClientIdentification::create($clientData, '1234');

        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, $account)
            ->withAttribute(ClientIdentification::class, $clientIdent);

        $response = $middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', StatusCodeInterface::STATUS_UNAUTHORIZED);
        $this->assertJsonValueEquals($json, '$.message', ResponseMessage::DATA_INVALID);
    }

    public function testAccountAccessAuthHasDuplicat(): void
    {
        $middleware = new PersistAuthenticationMiddleware($this->repository, $this->logger);
        $account = $this->hydrator->hydrate(Account::INVALID_DATA);
        $clientData = ClientIdentificationData::create('1', 'default');
        $clientIdent = ClientIdentification::create($clientData, '1234');
        $refreshToken = RefreshToken::fromString('1234');

        $request = $this->request->withAttribute(AccountInterface::AUTHENTICATED, $account)
            ->withAttribute(ClientIdentification::class, $clientIdent)
            ->withAttribute(RefreshToken::class, $refreshToken);

        $response = $middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', StatusCodeInterface::STATUS_UNAUTHORIZED);
        $this->assertJsonValueEquals($json, '$.message', ResponseMessage::DATA_INVALID);
    }
}
