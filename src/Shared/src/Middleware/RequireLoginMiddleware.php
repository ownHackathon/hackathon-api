<?php declare(strict_types=1);

namespace Exdrals\Shared\Middleware;

use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;
use Exdrals\Shared\Domain\Message\LogMessage;
use Exdrals\Shared\Domain\Message\StatusMessage;
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
