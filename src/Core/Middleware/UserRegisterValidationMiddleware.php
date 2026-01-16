<?php declare(strict_types=1);

namespace Core\Middleware;

use Core\Dto\HttpStatusCodeMessage;
use Core\Validator\RegisterValidator;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class UserRegisterValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RegisterValidator $validator
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            return new JsonResponse([
                new HttpStatusCodeMessage(
                    HTTP::STATUS_BAD_REQUEST,
                    'Registration failed',
                    $this->validator->getMessages()
                ),
            ], HTTP::STATUS_BAD_REQUEST);
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
