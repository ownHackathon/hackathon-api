<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Handler;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\EmptyResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Account\Identity\DTO\Token\RefreshToken;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Account\AccountService;
use ownHackathon\App\Http\DTO\HttpResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutHandler implements RequestHandlerInterface
{
    public function __construct(
        private AccountService $accountService,
    ) {
    }

    #[OA\Post(
        path: '/account/logout',
        operationId: 'logout',
        description: 'Terminates the user session by invalidating the provided refresh token in the database. ' .
        'The access token must be provided in the header for authentication, while the refresh token is required ' .
        'in the request body to identify the specific session to be closed.',
        summary: 'Log out the current user and invalidate the session',
        security: [['accessToken' => []]],
        tags: ['Account']
    )]
    #[OA\RequestBody(
        description: 'The refresh token that should be invalidated.',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'refreshToken',
                    description: 'The full refresh token string',
                    type: 'string',
                    example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'
                ),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: HTTP::STATUS_NO_CONTENT,
        description: 'Logout successful. The refresh token has been deleted and the session is invalidated.',
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Unauthorized. The access token is missing, expired, or the user could not be identified.',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        $refreshToken = $request->getAttribute(RefreshToken::class);

        $this->accountService->logout($account, $refreshToken);

        return new EmptyResponse(HTTP::STATUS_NO_CONTENT);
    }
}
