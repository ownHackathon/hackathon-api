<?php declare(strict_types=1);

namespace App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\Account\AccountPassword;
use App\DTO\Response\HttpResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountPasswordHandler implements RequestHandlerInterface
{
    #[OA\Patch(
        path: '/account/password/{token}',
        operationId: 'resetPasswordWithToken',
        description: 'Sets a new password for an account. This is the final step of the password recovery process. ' .
                     "The request requires a valid, non-expired reset token that was previously sent to the user's email address.",
        summary: 'Reset account password using a token',
        tags: ['Account'],
        parameters: [
            new OA\Parameter(
                name: 'token',
                description: 'The secret reset token received via email.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
    )]
    #[OA\RequestBody(
        description: 'The new password and its confirmation.',
        required: true,
        content: new OA\JsonContent(ref: AccountPassword::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Password has been successfully updated. The user can now log in with the new credentials.',
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: "Request failed due to validation errors. Possible reasons:\n" .
                     "1. The reset token is invalid or has already been used.\n" .
                     "2. The token has expired.\n" .
                     '3. The new password does not meet the security requirements (e.g., too short).',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([], HTTP::STATUS_OK);
    }
}
