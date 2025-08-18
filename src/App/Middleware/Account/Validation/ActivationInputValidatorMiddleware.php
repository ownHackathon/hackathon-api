<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\Validation;

use ownHackathon\App\DTO\AccountRegistrationDTO;
use ownHackathon\App\Validator\AccountActivationValidator;
use ownHackathon\Core\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\Message\ResponseMessage;
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
                'Name for Account and/or password are not valid',
                ResponseMessage::DATA_INVALID,
                [
                    'Account Name:' => $data['accountName'] ?? null,
                    'Validator-Message:' => $this->validator->getMessages(),
                ]
            );
        }

        $data = $this->validator->getValues();

        $response = AccountRegistrationDTO::fromString($data['accountName'], $data['password']);

        return $handler->handle($request->withAttribute(AccountRegistrationDTO::class, $response));
    }
}
