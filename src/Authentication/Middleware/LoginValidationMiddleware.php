<?php declare(strict_types=1);

namespace Authentication\Middleware;

use Authentication\Validator\LoginValidator;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class LoginValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoginValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            return new JsonResponse(['message' => 'Login failed', 'data' => $data], HTTP::STATUS_UNAUTHORIZED);
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
