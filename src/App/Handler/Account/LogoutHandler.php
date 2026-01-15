<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\DTO\Response\AuthenticationResponse;
use ownHackathon\App\DTO\Response\HttpResponseMessage;
use ownHackathon\Core\Enum\Message\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/account/logout',
        summary: 'Attempts to log out an account',
        tags: ['Account']
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: StatusMessage::SUCCESS->value,
        content: [new OA\JsonContent(ref: AuthenticationResponse::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: StatusMessage::UNAUTHORIZED_ACCESS->value,
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = HttpResponseMessage::create(HTTP::STATUS_OK, StatusMessage::SUCCESS);

        return new JsonResponse($response, $response->statusCode);
    }
}
