<?php declare(strict_types=1);

namespace App\Handler\Account;

use App\DTO\Account\Account;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\Account\AccountRegistration;
use App\DTO\Response\HttpResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountActivationHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account/activation/{token}',
        operationId: 'accountActivation',
        description: 'Completes the registration process. This endpoint validates the activation token ' .
                     'from the email and creates the actual user account with the provided username and password. ' .
                     "\n\nNote: The account is only persisted in the database after this step is successful.",
        summary: 'Finalize account registration and activation',
        tags: ['Account'],
        parameters: [
            new OA\Parameter(
                name: 'token',
                description: 'The secret activation token received via email.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
    )]
    #[OA\RequestBody(
        description: 'The account credentials to be set for the new account.',
        required: true,
        content: new OA\JsonContent(ref: AccountRegistration::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_CREATED,
        description: 'Account successfully created and activated. The user can now log in.',
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: "Validation failed. This can happen if:\n" .
                     "1. The token is invalid or has expired.\n" .
                     "2. The password does not meet the security requirements.\n" .
                     '3. The chosen account name is already taken.',
        content: [new OA\JsonContent(ref: HttpResponseMessage::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Account $account */
        $account = $request->getAttribute(Account::class);

        return new JsonResponse($account, HTTP::STATUS_CREATED, ['Location' => '/account/' . $account->uuid]);
    }
}
