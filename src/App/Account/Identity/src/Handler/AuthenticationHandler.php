<?php declare(strict_types=1);

namespace Exdrals\Identity\Handler;

use DateTimeImmutable;
use Exdrals\Identity\Domain\AccountAccessAuth;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Account\AuthenticationRequest;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Response\AuthenticationResponse;
use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Shared\Domain\Exception\HttpDuplicateEntryException;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Monolog\Level;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AuthenticationHandler implements RequestHandlerInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountAccessAuthRepositoryInterface $accountAccessAuthRepository,
        private RefreshTokenService $refreshTokenService,
        private AccessTokenService $accessTokenService,
        private AuthenticationService $authenticationService,
    ) {
    }

    #[OA\Post(
        path: '/account/authentication',
        operationId: 'accountAuthentication',
        description: 'Authenticates a user using their credentials. ' .
        'On success, it returns a short-lived **AccessToken** (for API authorization) ' .
        'and a long-lived **RefreshToken** (to obtain new access tokens).',
        summary: 'Authenticate user and issue tokens',
        tags: ['Account']
    )]
    #[OA\RequestBody(
        description: 'User credentials (email and password)',
        required: true,
        content: new OA\JsonContent(ref: AuthenticationRequest::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Authentication successful. The response contains both access and refresh tokens.',
        content: [new OA\JsonContent(ref: AuthenticationResponse::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Invalid credentials. The email or password provided is incorrect.',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_FORBIDDEN,
        description: 'Access denied. The credentials are correct, but the account is currently restricted. ' .
        'Possible reasons: Account is locked, disabled, or the email address has not been verified yet.',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getAttribute(AuthenticationRequest::class);

        $account = $this->accountRepository->findByEmail(EmailType::fromString($data->email));

        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data->email,
                ],
                Level::Warning
            );
        }

        if (!$this->authenticationService->isPasswordMatch($data->password, $account->password)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::PASSWORD_INCORRECT,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data->email,
                ],
                Level::Warning
            );
        }

        $account = $account->with(lastActionAt: new DateTimeImmutable());

        $this->accountRepository->update($account);

        $clientId = $request->getAttribute(ClientIdentification::class);
        $refreshToken = $this->refreshTokenService->generate($clientId);
        $accessToken = $this->accessTokenService->generate($account->uuid);

        $accountAccessAuth = new AccountAccessAuth(
            null,
            $account->id,
            'default',
            $refreshToken->refreshToken,
            $clientId->clientIdentificationData->userAgent,
            $clientId->identificationHash,
            new DateTimeImmutable()
        );
        try {
            $this->accountAccessAuthRepository->insert($accountAccessAuth);
        } catch (DuplicateEntryException $e) {
            throw new HttpDuplicateEntryException(
                IdentityLogMessage::DUPLICATE_SOURCE_LOGIN,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'Account' => $account->name,
                    'ClientID' => $clientId->identificationHash,
                    'ErrorMessage' => $e->getMessage(),
                ],
            );
        }

        $response = AuthenticationResponse::from($accessToken, $refreshToken);

        return new JsonResponse($response, HTTP::STATUS_OK);
    }
}
