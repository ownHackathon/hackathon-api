<?php declare(strict_types=1);

namespace Exdrals\Identity\Handler;

use Exdrals\Identity\DTO\Account\AccountAuthenticationData;
use Exdrals\Identity\DTO\Response\AuthenticationResponse;
use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use Exdrals\Identity\DTO\Token\AccessToken;
use Exdrals\Identity\DTO\Token\RefreshToken;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AuthenticationHandler implements RequestHandlerInterface
{
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
        content: new OA\JsonContent(ref: AccountAuthenticationData::class)
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
        $accessToken = $request->getAttribute(AccessToken::class);
        $refreshToken = $request->getAttribute(RefreshToken::class);

        $response = AuthenticationResponse::from($accessToken, $refreshToken);

        return new JsonResponse($response, HTTP::STATUS_OK);
    }
}
