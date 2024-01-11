<?php declare(strict_types=1);

namespace Test\Functional\UserControl;

use App\Entity\User;
use App\Service\User\UserService;
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

    /**
     *   ToDo - The Auth Token for verification is still missing here
     */
    public function testMeReturnValidUserByAuthenticatedUser(): void
    {
        $token = $this->app->container()->get('config')['token']['auth'];

        $user = new User();
        $user->setName('TestingUser')
            ->setEmail('testing@example.com')
            ->setPassword('myworld');

        /** @var UserService $userService */
        $userService = $this->app->container()->get(UserService::class);
        $userService->create($user);

        $request = new ServerRequest(uri: '/api/user/me', method: 'GET');

        $response = $this->app->dispatchRequest($request);

        self::assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }
}
