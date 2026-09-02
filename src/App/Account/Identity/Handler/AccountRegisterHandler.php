<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Handler;

use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\Account\Identity\Application\Port\AccountRegisterServiceInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Mailing\DTO\EMail;
use ownHackathon\Core\Http\DTO\HttpResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class AccountRegisterHandler implements RequestHandlerInterface
{
    public function __construct(
        private AccountRegisterServiceInterface $accountRegisterService,
    ) {
    }

    #[OA\Post(
        path: '/account',
        operationId: 'registerAccount',
        description: "Starts the account process for the given email address. \n\n" .
                     "**Security Note:** This endpoint always returns a 200 OK status to prevent user enumeration. " .
                     "The user will not know if an account already exists based on the API response.",
        summary: 'Endpoint to register a new user account or request a password reset.',
        tags: ['Account'],
    )]
    #[OA\RequestBody(
        description: 'The email address for the new account',
        required: true,
        content: new OA\JsonContent(ref: EMail::class),
    )]
    #[OA\Response(
        response: Http::STATUS_OK,
        description: "The request was processed successfully. Depending on the state of the account, one of the following actions will occur:\n" .
                     "1. **Account does not exist:** A token to activate the new account will be sent to the email address.\n" .
                     '2. **Account already exists:** A token to reset the password will be sent to the email address.',
    )]
    #[OA\Response(
        response: Http::STATUS_BAD_REQUEST,
        description: 'The provided email address is invalid.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class),
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = $request->getAttribute(EmailType::class);

        $this->accountRegisterService->register($email);


        return new JsonResponse([], Http::STATUS_OK);
    }
}
