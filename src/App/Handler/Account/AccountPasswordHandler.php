<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\DTO\Account\AccountPassword;
use ownHackathon\App\DTO\Response\HttpResponseMessage;
use ownHackathon\Core\Enum\Message\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountPasswordHandler implements RequestHandlerInterface
{
    #[OA\Patch(
        path: '/account/password/{token}',
        summary: 'Set Password to the account',
        tags: ['Account'],
        parameters: [
            new OA\Parameter(
                name: 'token',
                description: 'Token',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
    )]
    #[OA\RequestBody(
        description: 'Set Password for a Account',
        required: true,
        content: new OA\JsonContent(ref: AccountPassword::class)
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
