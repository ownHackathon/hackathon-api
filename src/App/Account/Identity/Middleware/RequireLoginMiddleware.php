<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware;

use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Http\Exception\HttpUnauthorizedException;
use ownHackathon\Core\SharedKernel\Domain\Message\LogMessage;
use ownHackathon\Core\SharedKernel\Domain\Message\StatusMessage;
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
