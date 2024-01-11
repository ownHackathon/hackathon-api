<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\UserService;
use App\Service\UserServiceFactory;
use App\Table\UserTable;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Ramsey\Uuid\Uuid;
use Test\Unit\Mock\MockContainer;
use Test\Unit\Mock\Table\MockUserTable;

class UserServiceFactoryTest extends AbstractService
{
    public function testCanCreateUserService(): void
    {
        $container = new MockContainer([
            UserTable::class => new MockUserTable(),
            ReflectionHydrator::class => $this->hydrator,
            NullableStrategy::class => $this->nullableStrategy,
            Uuid::class => Uuid::uuid4(),
        ]);

        $factory = new UserServiceFactory();

        $service = $factory($container);

        self::assertInstanceOf(UserService::class, $service);
    }
}
