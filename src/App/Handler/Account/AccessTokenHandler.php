<?php declare(strict_types=1);

namespace App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\Response\HttpResponseMessage;
use App\DTO\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AccessTokenHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/token/refresh',
        operationId: 'requestRegenerateAccessToken',
        description: "Generates a new short-lived access token using a valid refresh token. \n\n" .
                     'This endpoint allows the client to maintain a session without requiring the user ' .
                     'to re-enter their credentials. The refresh token must be provided via the ' .
                     'configured security mechanism (e.g., Authorization Header or HTTP-only Cookie).',
        summary: 'Refresh the access token',
        security: [['refreshToken' => []]],
        tags: ['Account']
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Successfully issued a new access token.',
        content: [new OA\JsonContent(ref: AccessToken::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Authentication failed. This happens if the refresh token is expired, ' .
                     'revoked, or invalid. The user must perform a full login again.',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $accessToken = $request->getAttribute(AccessToken::class);

        return new JsonResponse($accessToken, HTTP::STATUS_OK);
    }
}
