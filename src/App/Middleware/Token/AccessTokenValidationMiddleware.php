<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Monolog\Level;
use ownHackathon\App\Service\Token\AccessTokenService;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Message\ResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AccessTokenValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $tokenService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accessToken = $request->getHeaderLine('Authorization');

        if (empty($accessToken)) {
            throw new HttpUnauthorizedException(
                'No AccessToken provided.',
                ResponseMessage::ACCOUNT_UNAUTHORIZED,
                [],
                Level::Warning
            );
        }

        if (!$this->tokenService->isValid($accessToken)) {
            throw new HttpUnauthorizedException(
                'Expired access token.',
                ResponseMessage::TOKEN_EXPIRED,
                [
                    'Access Token:' => $accessToken,
                ],
            );
        }

        return $handler->handle($request);
    }
}
