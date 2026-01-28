<?php declare(strict_types=1);

namespace Exdrals\Identity\Handler;

use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Response\AuthenticationResponse;
use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Monolog\Level;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutHandler implements RequestHandlerInterface
{
    public function __construct(
        private AccountService $accountService,
    ) {
    }

    #[OA\Get(
        path: '/account/logout',
        operationId: 'logout',
        description: 'Terminates the user session by invalidating the current access token. ' .
        'Clients should delete all locally stored tokens (Access Token and Refresh Token) after a successful logout.',
        summary: 'Log out the current user',
        security: [['accessToken' => []]],
        tags: ['Account']
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Logout successful. The session has been invalidated.',
        content: [new OA\JsonContent(ref: AuthenticationResponse::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Unauthorized. The access token is missing, expired, or already invalid.',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        $clientId = $request->getAttribute(ClientIdentification::class);

        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGOUT_REQUIRES_AUTHENTICATION,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [],
                Level::Warning
            );
        }

        $this->accountService->logout($account, $clientId);

        $response = HttpResponseMessage::create(HTTP::STATUS_OK, IdentityStatusMessage::SUCCESS);

        return new JsonResponse($response, $response->statusCode);
    }
}
