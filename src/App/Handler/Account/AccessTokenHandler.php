<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\DTO\Response\HttpResponseMessage;
use ownHackathon\App\DTO\Token\AccessToken;
use ownHackathon\Core\Message\OAMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AccessTokenHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/token/refresh',
        summary: 'Return of a new access token',
        tags: ['Account'],
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: OAMessage::SUCCESS,
        content: [new OA\JsonContent(ref: AccessToken::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: OAMessage::UNAUTHORIZED_ACCESS,
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $accessToken = $request->getAttribute(AccessToken::class);

        return new JsonResponse($accessToken, HTTP::STATUS_OK);
    }
}
