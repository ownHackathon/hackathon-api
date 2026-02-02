<?php declare(strict_types=1);

namespace Exdrals\Identity\Handler;

use Exdrals\Identity\DTO\EMail\EMail;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordService;
use Exdrals\Mailing\Domain\EmailType;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AccountPasswordForgottenHandler implements RequestHandlerInterface
{
    public function __construct(
        private PasswordService $passwordService,
    ) {
    }

    #[OA\Post(
        path: '/account/password/forgotten',
        operationId: 'requestPasswordReset',
        description: "Initiates the password recovery process for the given email address. \n\n" .
                     '**Security Note:** To prevent user enumeration, this endpoint always returns a 200 OK status. ' .
                     'An attacker cannot determine whether an account exists for a specific email address by observing the API response.',
        summary: 'Request a token for password reset. Sending via E-Mail',
        tags: ['Account'],
    )]
    #[OA\RequestBody(
        description: 'The email address associated with the account.',
        required: true,
        content: new OA\JsonContent(ref: EMail::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'The request was accepted. If an account with the provided email address exists, ' .
                     'an email containing a password reset link will be sent shortly.',
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = $request->getAttribute(EmailType::class);
        $this->passwordService->sendTokenForInitiateResetPassword($email);

        return new JsonResponse([], HTTP::STATUS_OK);
    }
}
