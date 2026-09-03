<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware;

use App\Account\Identity\Domain\AccountInterface;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\SharedKernel\Domain\Message\LogMessage;
use Core\SharedKernel\Domain\Message\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class RequireLoginMiddleware implements MiddlewareInterface
{
    #[\Override]
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
