<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account\Validation;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\DTO\Account\AccountPassword;
use App\Account\Identity\Infrastructure\Validator\PasswordValidator;
use Core\Http\Exception\HttpInvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly final class PasswordInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private PasswordValidator $validator,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!is_array($data)) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::PASSWORD_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        try {
            $result = $this->validator->validate($data);

            if (!$result->valid()) {
                throw new HttpInvalidArgumentException(
                    IdentityLogMessage::PASSWORD_INVALID,
                    IdentityStatusMessage::INVALID_DATA,
                    [
                        'Validator Message:' => $result->getMessages()->toArray(),
                    ],
                );
            }

            /** @var array{password: string} $data */
            $data = $result->value();

            $password = AccountPassword::fromString($data['password']);
        } catch (HttpInvalidArgumentException $e) {
            throw $e;
        } catch (Throwable) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::PASSWORD_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }
        return $handler->handle($request->withAttribute(AccountPassword::class, $password));
    }
}
