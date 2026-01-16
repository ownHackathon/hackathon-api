<?php declare(strict_types=1);

namespace App\Middleware\Account\Validation;

use App\DTO\Account\AccountRegistration;
use App\Validator\AccountActivationValidator;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpInvalidArgumentException;
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
