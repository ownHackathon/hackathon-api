<?php declare(strict_types=1);

namespace App\Account\Identity\Handler;

use App\Account\Identity\DTO\Client\ClientIdentification;
use App\Account\Identity\DTO\Token\AccessToken;
use App\Account\Identity\DTO\Token\RefreshToken;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Core\Http\DTO\HttpResponseMessage;
use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class AccessTokenHandler implements RequestHandlerInterface
{
    public function __construct(
        private RefreshTokenService $refreshTokenService,
    ) {
    }

    #[OA\Get(
        path: '/token/refresh',
        operationId: 'requestRegenerateAccessToken',
        description: "Generates a new short-lived access token using a valid refresh token. \n\n" .
        'This endpoint allows the client to maintain a session without requiring the user ' .
        'to re-enter their credentials. The refresh token must be provided via the ' .
        'configured security mechanism (e.g., Authorization Header or HTTP-only Cookie).',
        summary: 'Refresh the access token',
        security: [['refreshToken' => []]],
        tags: ['Account'],
    )]
    #[OA\Response(
        response: Http::STATUS_OK,
        description: 'Successfully issued a new access token.',
        content: [new OA\JsonContent(ref: AccessToken::class)],
    )]
    #[OA\Response(
        response: Http::STATUS_UNAUTHORIZED,
        description: 'Authentication failed. This happens if the refresh token is expired, ' .
        'revoked, or invalid. The user must perform a full login again.',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)],
    )]
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $refreshToken = $request->getAttribute(RefreshToken::class);
        $clientIdentification = $request->getAttribute(ClientIdentification::class);

        $refreshedAccessToken = $this->refreshTokenService->refresh($refreshToken, $clientIdentification);

        return new JsonResponse($refreshedAccessToken, Http::STATUS_OK);
    }
}
