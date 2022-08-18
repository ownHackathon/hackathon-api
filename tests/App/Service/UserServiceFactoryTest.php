<?php declare(strict_types=1);

namespace AppTest\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\UserService;
use App\Service\UserServiceFactory;
use App\Table\UserTable;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

class UserServiceFactoryTest extends AbstractServiceTest
{
    public function testCanCreateUserService(): void
    {
        $userTable = $this->createMock(UserTable::class);
        $strategy = $this->createMock(NullableStrategy::class);
        $container = $this->createMock(ContainerInterface::class);
        $uuid = Uuid::uuid4();

        $container->expects($this->exactly(4))
            ->method('get')->will(
                $this->returnValueMap(
                    [
                        [UserTable::class, $userTable],
                        [ReflectionHydrator::class, $this->hydrator],
                        [NullableStrategy::class, $strategy],
                        [Uuid::class, $uuid],
                    ],
                )
            );

        $userServiceFactory = new UserServiceFactory();
        $userService = $userServiceFactory($container);

        $this->assertInstanceOf(UserService::class, $userService);
    }
}
