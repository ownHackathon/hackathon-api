<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\DTO\RefreshToken;
use ownHackathon\App\Service\Token\RefreshTokenService;
use ownHackathon\Core\Message\ResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

readonly class RefreshTokenValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RefreshTokenService $tokenService,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $refreshToken = $request->getHeaderLine('Authentication');

        if (!$this->tokenService->isValid($refreshToken)) {
            $this->logger->notice('Invalid refresh token', [
                'Refresh Token:' => $refreshToken,
            ]);
            return new JsonResponse(
                HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::TOKEN_INVALID),
                HTTP::STATUS_UNAUTHORIZED
            );
        }

        $refreshToken = RefreshToken::fromString($refreshToken);

        return $handler->handle($request->withAttribute(RefreshToken::class, $refreshToken));
    }
}
