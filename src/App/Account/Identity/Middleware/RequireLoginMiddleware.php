<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware;

use Exdrals\Core\Shared\Domain\Exception\HttpUnauthorizedException;
use Exdrals\Core\Shared\Domain\Message\LogMessage;
use Exdrals\Core\Shared\Domain\Message\StatusMessage;
use Exdrals\Identity\Domain\AccountInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class RequireLoginMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountInterface|null $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                LogMessage::UNAUTHORIZED_ACCESS,
                StatusMessage::UNAUTHORIZED_ACCESS,
                [],
            );
        }

        return $handler->handle($request);
    }
}
