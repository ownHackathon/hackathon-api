<?php declare(strict_types=1);

namespace App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\Response\AuthenticationResponse;
use App\DTO\Response\HttpResponseMessage;
use Core\Enum\Message\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutHandler implements RequestHandlerInterface
{
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
        $response = HttpResponseMessage::create(HTTP::STATUS_OK, StatusMessage::SUCCESS);

        return new JsonResponse($response, $response->statusCode);
    }
}
