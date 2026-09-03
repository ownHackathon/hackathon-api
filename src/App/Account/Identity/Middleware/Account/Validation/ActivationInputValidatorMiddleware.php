<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account\Validation;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\DTO\Account\AccountRegistration;
use App\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use Core\Http\Exception\HttpInvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly final class ActivationInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountActivationValidator $validator,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!is_array($data)) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACCOUNT_NAME_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        try {
            $this->validator->setData($data);

            if (!$this->validator->isValid()) {
                throw new HttpInvalidArgumentException(
                    IdentityLogMessage::ACCOUNT_NAME_INVALID,
                    IdentityStatusMessage::INVALID_DATA,
                    [
                        'Account Name:' => $data['accountName'] ?? null,
                        'Validator-Message:' => $this->validator->getMessages(),
                    ],
                );
            }

            /** @var array{accountName: string, password: string} $data */
            $data = $this->validator->getValues();

            $response = AccountRegistration::fromString($data['accountName'], $data['password']);
        } catch (HttpInvalidArgumentException $e) {
            throw $e;
        } catch (Throwable) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACCOUNT_NAME_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        return $handler->handle($request->withAttribute(AccountRegistration::class, $response));
    }
}
