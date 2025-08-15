<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\DTO\EMailDTO;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\Core\Message\OAMessage;

readonly class AccountRegisterHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account',
        description: 'Create new Account',
        summary: 'Create new Account',
        tags: ['Account'],
    )]
    #[OA\RequestBody(
        description: 'E-Mail for register Account',
        required: true,
        content: new OA\JsonContent(ref: EMailDTO::class)
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
