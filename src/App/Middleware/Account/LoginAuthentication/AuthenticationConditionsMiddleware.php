<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\Core\Message\ResponseMessage;

readonly class AuthenticationConditionsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->hasHeader('Authentication') || $request->hasHeader('Authorization')) {
            $this->logger->notice(
                'Login failed because the authentication or authorization header is already set.',
                [
                    'Header:' => $request->getHeaders(),
                ]
            );

            $message = HttpFailureMessage::create(
                HTTP::STATUS_FORBIDDEN,
                ResponseMessage::ACCOUNT_ALREADY_AUTHENTICATED
            );

            return new JsonResponse($message, HTTP::STATUS_FORBIDDEN);
        }

        return $handler->handle($request);
    }
}
