<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\DTO\Account\AccountRegistration;
use ownHackathon\App\DTO\Response\HttpResponseMessage;
use ownHackathon\Core\Message\OAMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountActivationHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account/activation/{token}',
        summary: 'Activate the account',
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
        description: 'E-Mail for register Account',
        required: true,
        content: new OA\JsonContent(ref: AccountRegistration::class)
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
