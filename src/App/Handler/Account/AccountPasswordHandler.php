<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\DTO\AccountPasswordDTO;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\Core\Message\OAMessage;

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
        content: new OA\JsonContent(ref: AccountPasswordDTO::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: OAMessage::SUCCESS,
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: OAMessage::BAD_REQUEST,
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([], HTTP::STATUS_OK);
    }
}
