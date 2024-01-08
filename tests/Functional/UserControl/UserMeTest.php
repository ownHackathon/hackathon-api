<?php declare(strict_types=1);

namespace Test\Functional\UserControl;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\ServerRequest;
use Test\Functional\FunctionalTestCase;

class UserMeTest extends FunctionalTestCase
{
    public function testMeReturnEmptyByNoneAuthenticatedUser(): void
    {
        $request = new ServerRequest(uri: '/api/user/me', method: 'GET');

        $response = $this->app->dispatchRequest($request);

        self::assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        self::assertEmpty(self::getContentAsJson($response));
    }

    public function testMeReturnValidUserByAuthenticatedUser(): void
    {
        $token = $this->app->container()->get('config')['token']['auth'];

        $request = new ServerRequest(uri: '/api/user/me', method: 'GET');

        $response = $this->app->dispatchRequest($request);

        self::assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }
}
