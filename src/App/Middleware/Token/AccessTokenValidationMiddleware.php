<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\Service\Token\AccessTokenService;
use ownHackathon\Core\Message\ResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

readonly class AccessTokenValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $tokenService,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accessToken = $request->getHeaderLine('Authorization');

        if (empty($accessToken)) {
            $this->logger->warning('No AccessToken provided');

            $message = HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::ACCOUNT_UNAUTHORIZED);

            return new JsonResponse($message, $message->statusCode);
        }

        if (!$this->tokenService->isValid($accessToken)) {
            $this->logger->notice('Expired access token', [
                'Access Token:' => $accessToken,
            ]);

            $message = HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::TOKEN_EXPIRED);

            return new JsonResponse($message, $message->statusCode);
        }

        return $handler->handle($request);
    }
}
