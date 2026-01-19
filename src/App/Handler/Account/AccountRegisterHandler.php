<?php declare(strict_types=1);

namespace App\Handler\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use App\DTO\EMail\EMail;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AccountRegisterHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/account',
        description: "Starts the account process for the given email address. \n\n" .
                     "**Security Note:** This endpoint always returns a 200 OK status to prevent user enumeration. " .
                     "The user will not know if an account already exists based on the API response.",
        summary: 'Endpoint to register a new user account or request a password reset.',
        tags: ['Account'],
    )]
    #[OA\RequestBody(
        description: 'The email address for the new account',
        required: true,
        content: new OA\JsonContent(ref: EMail::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: "The request was processed successfully. Depending on the state of the account, one of the following actions will occur:\n" .
                     "1. **Account does not exist:** A token to activate the new account will be sent to the email address.\n" .
                     '2. **Account already exists:** A token to reset the password will be sent to the email address.',
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([], HTTP::STATUS_OK);
    }
}
