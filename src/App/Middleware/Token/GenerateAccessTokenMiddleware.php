<?php declare(strict_types=1);

namespace App\Middleware\Token;

use App\DTO\Token\AccessToken;
use App\Service\Token\AccessTokenService;
use Core\Entity\Account\AccountInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class GenerateAccessTokenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $accessTokenService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountInterface $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        $accessToken = $this->accessTokenService->generate($account->uuid);

        $accessToken = AccessToken::fromString($accessToken);

        return $handler->handle($request->withAttribute(AccessToken::class, $accessToken));
    }
}
