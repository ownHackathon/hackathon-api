<?php declare(strict_types=1);

namespace App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\Account\AccountAuthenticationData;
use App\DTO\Response\AuthenticationResponse;
use App\DTO\Response\HttpResponseMessage;
use App\DTO\Token\AccessToken;
use App\DTO\Token\RefreshToken;
use Core\Enum\Message\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AuthenticationHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account/authentication',
        summary: 'Attempts to log in an account using transferred data',
        tags: ['Account']
    )]
    #[OA\RequestBody(
        description: 'Account data for authentication',
        required: true,
        content: new OA\JsonContent(ref: AccountAuthenticationData::class)
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
    #[OA\Response(
        response: HTTP::STATUS_FORBIDDEN,
        description: StatusMessage::FORBIDDEN->value,
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $accessToken = $request->getAttribute(AccessToken::class);
        $refreshToken = $request->getAttribute(RefreshToken::class);

        $response = AuthenticationResponse::from($accessToken, $refreshToken);

        return new JsonResponse($response, HTTP::STATUS_OK);
    }
}
