<?php declare(strict_types=1);

namespace FunctionalTest\Root\Account;

use DateTimeImmutable;
use Envms\FluentPDO\Query;
use Exdrals\Identity\Domain\AccountAccessAuth;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Client\ClientIdentificationData;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepository;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountRepository;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use FunctionalTest\AbstractFunctional;
use Laminas\Diactoros\ServerRequest;
use PDO;
use PHPUnit\Framework\Assert;
use Shared\Domain\Enum\Message\StatusMessage;
use UnitTest\JsonRequestHelper;

class AccessTokenHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;

    private const string USER_AGENT = 'Test Browser';
    private const string UNEXPECTED_USER_AGENT = 'Unexpected Test Browser';
    private const string CLIENT_IDENTIFICATION = '1';
    private const string UNEXPECTED_CLIENT_IDENTIFICATION = '2';
    // phpcs:disable
    private const string EXPIRED_REFRESH_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJhdWQiOiJsb2NhbGhvc3QiLCJpYXQiOjE3NTEwNjIwODEsImV4cCI6MTc1MTA2MjA4MiwiaWRlbnQiOiIxOTU1MTc5YWFiMDMzZGUxYzgzZDU5M2JhZTg0NWQyZDY5ZDU4OGU5NGVkYTczZmNlMzAxYmZkOGIwNzljNmRmMTU5ZDIwMjEzODhjNDExMDkzMGVmY2FhYjhmNDY3YjRiYjg1MDk0NmZkYmM0MjkzMDkwMmI1NzgyOTY2OGYzYiJ9.YoqEvXmX_Sh4PFIPRqv8w9hu97GIcBSSkmQFA8Q-DVFmhCWl8prbZGSYR_5Dv5h6gYRZMzcqPFh5bhNXUaxqUg';
    private const string INVALID_REFRESH_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJhdWQiOiJsb2NhbGhvc3QiLCJpYXQiOjE3NTEwNjIyMTcsImV4cCI6MTc1ODMxOTgxNywiaWRlbnQiOiIxOTU1MTc5YWFiMDMzZGUxYzgzZDU5M2JhZTg0NWQyZDY5ZDU4OGU5NGVkYTczZmNlMzAxYmZkOGIwNzljNmRmMTU5ZDIwMjEzODhjNDExMDkzMGVmY2FhYjhmNDY3YjRiYjg1MDk0NmZkYmM0MjkzMDkwMmI1NzgyOTY2OGYzYiJ9.pbhVeCNLsUpOgISg_SiI91JALAWPsuIdlOXCKhI008yzT6SbV3gdJPdtGl0u1mC4Dlpx1WtDpN1We-0nRgaVCA';
    // phpcs:enable

    private AccountRepository $accountRepository;
    private AccountAccessAuthRepository $accountAccessAuthRepository;
    private AccessTokenService $accessTokenService;
    private ClientIdentificationData $clientIdentificationData;
    private ClientIdentification $clientIdentification;
    private ClientIdentificationService $clientIdentificationService;
    private RefreshTokenService $refreshTokenService;
    private string $refreshToken;
    private PDO $PDO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = $this->container->get(AccountRepositoryInterface::class);
        $this->accountAccessAuthRepository = $this->container->get(AccountAccessAuthRepository::class);
        $this->clientIdentificationService = $this->container->get(ClientIdentificationService::class);
        $this->clientIdentificationData = ClientIdentificationData::create(
            self::CLIENT_IDENTIFICATION,
            self::USER_AGENT
        );
        $clientIdentifcationHash = $this->clientIdentificationService->getClientIdentificationHash(
            $this->clientIdentificationData
        );
        $this->clientIdentification = ClientIdentification::create(
            $this->clientIdentificationData,
            $clientIdentifcationHash
        );
        $this->refreshTokenService = $this->container->get(RefreshTokenService::class);
        $this->accessTokenService = $this->container->get(AccessTokenService::class);
        $this->refreshToken = $this->refreshTokenService->generate($this->clientIdentification);
        /** @var Query $query */
        $query = $this->container->get(Query::class);
        $this->PDO = $query->getPdo();
    }

    public function testReturnANewAccessToken(): void
    {
        $userAccount = $this->accountRepository->findByName('User');
        $accountAccessAuth = new AccountAccessAuth(
            null,
            $userAccount->id,
            'Testing',
            $this->refreshToken,
            self::USER_AGENT,
            $this->clientIdentificationService->getClientIdentificationHash($this->clientIdentificationData),
            new DateTimeImmutable()
        );
        $this->accountAccessAuthRepository->insert($accountAccessAuth);
        $accountAccessAuthId = (int)$this->PDO->lastInsertId();

        $request = new ServerRequest(
            uri: '/api/token/refresh',
            method: 'GET',
            headers: [
                'x-ident' => self::CLIENT_IDENTIFICATION,
                'Authentication' => $this->refreshToken,
                'User-Agent' => self::USER_AGENT,
            ]
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'accessToken' => Assert::isType('string'),
        ]));

        $isAccessToken = $this->accessTokenService->isValid($content['accessToken']);

        $this->assertTrue($isAccessToken);

        $this->accountAccessAuthRepository->deleteById($accountAccessAuthId);
    }

    public function testGivenRefreshTokenIsInvalid(): void
    {
        $userAccount = $this->accountRepository->findByName('User');
        $accountAccessAuth = new AccountAccessAuth(
            null,
            $userAccount->id,
            'Testing',
            $this->refreshToken,
            self::USER_AGENT,
            $this->clientIdentificationService->getClientIdentificationHash($this->clientIdentificationData),
            new DateTimeImmutable()
        );
        $this->accountAccessAuthRepository->insert($accountAccessAuth);
        $accountAccessAuthId = (int)$this->PDO->lastInsertId();

        $request = new ServerRequest(
            uri: '/api/token/refresh',
            method: 'GET',
            headers: [
                'x-ident' => self::CLIENT_IDENTIFICATION,
                'Authentication' => self::INVALID_REFRESH_TOKEN,
                'User-Agent' => self::USER_AGENT,
            ]
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(IdentityStatusMessage::TOKEN_INVALID, $content['message']);

        $this->accountAccessAuthRepository->deleteById($accountAccessAuthId);
    }

    public function testGivenRefreshTokenIsExpired(): void
    {
        $userAccount = $this->accountRepository->findByName('User');
        $accountAccessAuth = new AccountAccessAuth(
            1,
            $userAccount->id,
            'Testing',
            $this->refreshToken,
            self::USER_AGENT,
            $this->clientIdentificationService->getClientIdentificationHash($this->clientIdentificationData),
            new DateTimeImmutable()
        );
        $this->accountAccessAuthRepository->insert($accountAccessAuth);
        $accountAccessAuthId = (int)$this->PDO->lastInsertId();

        $request = new ServerRequest(
            uri: '/api/token/refresh',
            method: 'GET',
            headers: [
                'x-ident' => self::CLIENT_IDENTIFICATION,
                'Authentication' => self::EXPIRED_REFRESH_TOKEN,
                'User-Agent' => self::USER_AGENT,
            ]
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(IdentityStatusMessage::TOKEN_INVALID, $content['message']);

        $this->accountAccessAuthRepository->deleteById($accountAccessAuthId);
    }

    public function testGivenRefreshTokenIsNotPersistenInDatabase(): void
    {
        $request = new ServerRequest(
            uri: '/api/token/refresh',
            method: 'GET',
            headers: [
                'x-ident' => self::CLIENT_IDENTIFICATION,
                'Authentication' => $this->refreshToken,
                'User-Agent' => self::USER_AGENT,
            ]
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(IdentityStatusMessage::TOKEN_NOT_PERSISTENT, $content['message']);
    }

    public function testUnexpectedClientIdentification(): void
    {
        $userAccount = $this->accountRepository->findByName('User');
        $accountAccessAuth = new AccountAccessAuth(
            1,
            $userAccount->id,
            'Testing',
            $this->refreshToken,
            self::USER_AGENT,
            $this->clientIdentificationService->getClientIdentificationHash($this->clientIdentificationData),
            new DateTimeImmutable()
        );
        $this->accountAccessAuthRepository->insert($accountAccessAuth);
        $accountAccessAuthId = (int)$this->PDO->lastInsertId();

        $request = new ServerRequest(
            uri: '/api/token/refresh',
            method: 'GET',
            headers: [
                'x-ident' => self::UNEXPECTED_CLIENT_IDENTIFICATION,
                'Authentication' => $this->refreshToken,
                'User-Agent' => self::USER_AGENT,
            ]
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(IdentityStatusMessage::CLIENT_UNEXPECTED, $content['message']);

        $this->accountAccessAuthRepository->deleteById($accountAccessAuthId);
    }

    public function testUnexpectedUserAgent(): void
    {
        $userAccount = $this->accountRepository->findByName('User');
        $accountAccessAuth = new AccountAccessAuth(
            1,
            $userAccount->id,
            'Testing',
            $this->refreshToken,
            self::USER_AGENT,
            $this->clientIdentificationService->getClientIdentificationHash($this->clientIdentificationData),
            new DateTimeImmutable()
        );
        $this->accountAccessAuthRepository->insert($accountAccessAuth);
        $accountAccessAuthId = (int)$this->PDO->lastInsertId();

        $request = new ServerRequest(
            uri: '/api/token/refresh',
            method: 'GET',
            headers: [
                'x-ident' => self::CLIENT_IDENTIFICATION,
                'Authentication' => $this->refreshToken,
                'User-Agent' => self::UNEXPECTED_USER_AGENT,
            ]
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(IdentityStatusMessage::CLIENT_UNEXPECTED, $content['message']);

        $this->accountAccessAuthRepository->deleteById($accountAccessAuthId);
    }
}
