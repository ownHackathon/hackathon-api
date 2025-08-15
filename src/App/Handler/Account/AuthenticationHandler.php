<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\DTO\AccessToken;
use ownHackathon\App\DTO\AccountAuthenticationData;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\DTO\AuthenticationResponse;
use ownHackathon\App\DTO\RefreshToken;
use ownHackathon\Core\Message\OAMessage;

readonly class AuthenticationHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account/authentication',
        summary: 'Attempts to log in an account using transferred data',
        tags: ['Account']
    )]
    #[OA\RequestBody(
        description: 'Account data for authentication',
        required: true,
        content: new OA\JsonContent(ref: AccountAuthenticationData::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: OAMessage::SUCCESS,
        content: [new OA\JsonContent(ref: AuthenticationResponse::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: OAMessage::UNAUTHORIZED_ACCESS,
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_FORBIDDEN,
        description: OAMessage::FORBIDDEN,
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
