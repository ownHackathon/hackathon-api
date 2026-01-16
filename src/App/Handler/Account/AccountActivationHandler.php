<?php declare(strict_types=1);

namespace App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\Account\AccountRegistration;
use App\DTO\Response\HttpResponseMessage;
use Core\Enum\Message\StatusMessage;
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
