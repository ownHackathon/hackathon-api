<?php declare(strict_types=1);

namespace App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\Response\HttpResponseMessage;
use App\DTO\Token\AccessToken;
use Core\Enum\Message\StatusMessage;
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
        description: StatusMessage::SUCCESS->value,
        content: [new OA\JsonContent(ref: AccessToken::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: StatusMessage::UNAUTHORIZED_ACCESS->value,
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $accessToken = $request->getAttribute(AccessToken::class);

        return new JsonResponse($accessToken, HTTP::STATUS_OK);
    }
}
