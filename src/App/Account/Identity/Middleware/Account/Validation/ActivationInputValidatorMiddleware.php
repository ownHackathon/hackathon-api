<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Account\Validation;

use ownHackathon\App\Http\Exception\HttpInvalidArgumentException;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\DTO\Account\AccountRegistration;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ActivationInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountActivationValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACCOUNT_NAME_INVALID,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'Account Name:' => $data['accountName'] ?? null,
                    'Validator-Message:' => $this->validator->getMessages(),
                ]
            );
        }

        $data = $this->validator->getValues();

        $response = AccountRegistration::fromString($data['accountName'], $data['password']);

        return $handler->handle($request->withAttribute(AccountRegistration::class, $response));
    }
}
