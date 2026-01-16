<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\DTO\EMail\EMail;
use ownHackathon\App\DTO\Response\HttpResponseMessage;
use ownHackathon\Core\Enum\Message\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AccountRegisterHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account',
        description: 'Create Account',
        summary: 'Endpoint to register a new user account.',
        tags: ['Account'],
    )]
    #[OA\RequestBody(
        description: 'The email address for the new account',
        required: true,
        content: new OA\JsonContent(ref: EMail::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: StatusMessage::SUCCESS->value,
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: StatusMessage::BAD_REQUEST->value,
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([], HTTP::STATUS_OK);
    }
}
