<?php declare(strict_types=1);

namespace App\Middleware\Authentication;

use Core\Validator\PasswordForgottenEmailValidator;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class UserPasswordForgottenValidator implements MiddlewareInterface
{
    public function __construct(
        private PasswordForgottenEmailValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            return new JsonResponse([
                'message' => 'Validation fault',
                'data' => $this->validator->getMessages(),
            ], HTTP::STATUS_NOT_FOUND);
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
