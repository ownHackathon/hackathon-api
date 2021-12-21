<?php declare(strict_types=1);

namespace App\Service;

use App\Table\UserTable;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\NullableStrategy;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class UserServiceFactoryTest extends TestCase
{
    public function testCanCreateUserService(): void
    {
        $userTable = $this->createMock(UserTable::class);
        $hydrator = $this->createMock(ReflectionHydrator::class);
        $strategy = $this->createMock(NullableStrategy::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->method('get')->will(
            $this->returnValueMap(
                [
                    [UserTable::class, $userTable],
                    [ReflectionHydrator::class, $hydrator],
                    [NullableStrategy::class, $strategy],
                ],
            )
        );

        $userServiceFactory = new UserServiceFactory();
        $userService = $userServiceFactory($container);

        $this->assertInstanceOf(UserService::class, $userService);
    }
}
