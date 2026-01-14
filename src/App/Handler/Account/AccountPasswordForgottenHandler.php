<?php declare(strict_types=1);

namespace ownHackathon\App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\DTO\EMail\EMail;
use ownHackathon\Core\Message\OAMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountPasswordForgottenHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account/password/forgotten',
        summary: 'Creates a token for password reset. Sending via E-Mail',
        tags: ['Account'],
    )]
    #[OA\RequestBody(
        description: 'Set Password for a Account',
        required: true,
        content: new OA\JsonContent(ref: EMail::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: OAMessage::SUCCESS,
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([], HTTP::STATUS_OK);
    }
}
