<?php declare(strict_types=1);

namespace Test\Unit\App\Handler\Authentication;

use App\Entity\User;
use App\Handler\Authentication\LoginHandler;
use Laminas\Diactoros\Response\JsonResponse;
use Test\Data\Entity\UserTestEntity;
use Test\Unit\App\Handler\AbstractHandler;

use function json_decode;

class LoginHandlerTest extends AbstractHandler
{
    public function testUserIsLoggedIn(): void
    {
        $handler = new LoginHandler('abcd', 100);

        $response = $handler->handle(
            $this->request->withAttribute(User::AUTHENTICATED_USER, new User(...UserTestEntity::getDefaultUserValue()))
        );

        $responseData = $response->getBody()->getContents();

        $responseDataAsArray = json_decode($responseData, true);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertIsString($responseData);
        self::assertJson($responseData);
        self::assertIsArray($responseDataAsArray);
        self::assertArrayHasKey('token', $responseDataAsArray);
    }
}
