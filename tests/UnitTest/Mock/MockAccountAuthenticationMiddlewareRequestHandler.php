<?php declare(strict_types=1);

namespace UnitTest\Mock;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Core\Entity\Account\AccountInterface;

class MockAccountAuthenticationMiddlewareRequestHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        $response = new MockResponse();

        if ($account instanceof AccountInterface) {
            return $response->withHeader('Authorization', 'true');
        }

        return $response;
    }
}
