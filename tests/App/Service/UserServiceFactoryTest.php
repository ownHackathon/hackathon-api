<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\UserService;
use App\Service\UserServiceFactory;
use App\Table\UserTable;
use App\Test\Mock\MockContainer;
use App\Test\Mock\Table\MockUserTable;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Ramsey\Uuid\Uuid;

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

        $this->assertInstanceOf(UserService::class, $service);
    }
}
