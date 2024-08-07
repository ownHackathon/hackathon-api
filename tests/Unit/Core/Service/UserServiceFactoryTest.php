<?php declare(strict_types=1);

namespace Test\Unit\Core\Service;

use App\Service\User\UserService;
use App\Service\User\UserServiceFactory;
use Core\Hydrator\ReflectionHydrator;
use Core\Repository\UserRepository;
use Laminas\Hydrator\Strategy\DateTimeImmutableFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Ramsey\Uuid\Uuid;
use Test\Unit\Mock\MockContainer;
use Test\Unit\Mock\Table\MockUserTable;

class UserServiceFactoryTest extends AbstractService
{
    public function testCanCreateUserService(): void
    {
        $container = new MockContainer([
            UserRepository::class => new MockUserTable(),
            ReflectionHydrator::class => $this->hydrator,
            NullableStrategy::class => $this->nullableStrategy,
            DateTimeImmutableFormatterStrategy::class => $this->dateTimeImmutableFormatterStrategy,
            Uuid::class => Uuid::uuid4(),
        ]);

        $factory = new UserServiceFactory();

        $service = $factory($container);

        self::assertInstanceOf(UserService::class, $service);
    }
}
