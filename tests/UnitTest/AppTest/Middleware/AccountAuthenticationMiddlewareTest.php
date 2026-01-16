<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use App\Middleware\Account\RequestAuthenticationMiddleware;
use App\Service\Token\AccessTokenService;
use Core\Exception\HttpUnauthorizedException;
use Core\Repository\AccountRepositoryInterface;
use Core\Utils\UuidFactory;
use Core\Utils\UuidFactoryInterface;
use FunctionalTest\Mock\NullLogger;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\MockAccountAuthenticationMiddlewareRequestHandler;
use UnitTest\Mock\Repository\MockAccountRepository;
use UnitTest\Mock\Repository\MockAccountRepositoryAccountAuthenticationMiddlewareInvalidToken;
use UnitTest\Mock\Service\MockAccessTokenService;
use UnitTest\Mock\Service\MockAccessTokenServiceWithoutDuration;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class AccountAuthenticationMiddlewareTest extends AbstractTestMiddleware
{
    private AccessTokenService $accessTokenService;
    private AccountRepositoryInterface $accountRepository;
    private LoggerInterface $logger;
    private UuidFactoryInterface $uuidFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accessTokenService = new MockAccessTokenService();
        $this->accountRepository = new MockAccountRepository();
        $this->logger = new NullLogger();
        $this->uuidFactory = new UuidFactory();
    }

    public function testAccountAuthenticatedIsGuest(): void
    {
        $middleware = new RequestAuthenticationMiddleware(
            $this->accessTokenService,
            $this->accountRepository,
            $this->uuidFactory,
            $this->logger,
        );
        $handler = new MockAccountAuthenticationMiddlewareRequestHandler();
        $response = $middleware->process($this->request, $handler);
        $header = $response->getHeaderLine('Authorization');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
        $this->assertSame('', $header);
    }

    public function testAccountSuccessfulAuthenticated(): void
    {
        $accessToken = $this->accessTokenService->generate($this->uuidFactory->fromString(Account::UUID));
        $request = $this->request->withHeader('Authorization', $accessToken);

        $middleware = new RequestAuthenticationMiddleware(
            $this->accessTokenService,
            $this->accountRepository,
            $this->uuidFactory,
            $this->logger,
        );

        $handler = new MockAccountAuthenticationMiddlewareRequestHandler();
        $response = $middleware->process($request, $handler);
        $header = $response->getHeaderLine('Authorization');

        $this->assertNotInstanceOf(JsonResponse::class, $response);
        $this->assertSame('true', $header);
    }

    public function testTokenHasExpired(): void
    {
        $accessTokenService = new MockAccessTokenServiceWithoutDuration();
        $accessToken = $accessTokenService->generate($this->uuidFactory->fromString(Account::UUID));
        $request = $this->request->withHeader('Authorization', $accessToken);

        $middleware = new RequestAuthenticationMiddleware(
            $this->accessTokenService,
            $this->accountRepository,
            $this->uuidFactory,
            $this->logger,
        );

        $this->expectException(HttpUnauthorizedException::class);
        $middleware->process($request, $this->handler);
    }

    public function testTokenHasInvalid(): void
    {
        $accessToken = $this->accessTokenService->generate($this->uuidFactory->fromString(Account::UUID));
        $request = $this->request->withHeader('Authorization', $accessToken);
        $accountRepository = new MockAccountRepositoryAccountAuthenticationMiddlewareInvalidToken();
        $middleware = new RequestAuthenticationMiddleware(
            $this->accessTokenService,
            $accountRepository,
            $this->uuidFactory,
            $this->logger,
        );

        $this->expectException(HttpUnauthorizedException::class);
        $middleware->process($request, $this->handler);
    }
}
