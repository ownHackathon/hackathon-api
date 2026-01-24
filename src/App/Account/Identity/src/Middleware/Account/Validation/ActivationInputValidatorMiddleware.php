<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Account\Validation;

use Exdrals\Account\Identity\DTO\Account\AccountRegistration;
use Exdrals\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpInvalidArgumentException;

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
                LogMessage::ACCOUNT_NAME_INVALID,
                StatusMessage::INVALID_DATA,
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
