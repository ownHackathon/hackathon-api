<?php declare(strict_types=1);

namespace App\Service\User;

use App\Enum\UserRole;
use App\Hydrator\ReflectionHydrator;
use App\Repository\UserRepository;
use App\System\Hydrator\Strategy\UuidStrategy;
use Laminas\Hydrator\Strategy\BackedEnumStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

readonly class UserServiceFactory
{
    public function __invoke(ContainerInterface $container): UserService
    {
        /** @var UserRepository $repository */
        $repository = $container->get(UserRepository::class);

        /** @var ReflectionHydrator $hydrator */
        $hydrator = clone $container->get(ReflectionHydrator::class);

        /** @var DateTimeFormatterStrategy $dateTimeFormatterStrategy */
        $dateTimeFormatterStrategy = $container->get(DateTimeFormatterStrategy::class);

        /** @var Uuid $uuid */
        $uuid = $container->get(Uuid::class);

        $hydrator->addStrategy(
            'registrationAt',
            $dateTimeFormatterStrategy,
        );

        $hydrator->addStrategy(
            'lastActionAt',
            $dateTimeFormatterStrategy,
        );

        $hydrator->addStrategy(
            'role',
            new BackedEnumStrategy(UserRole::class)
        );

        $hydrator->addStrategy(
            'uuid',
            new UuidStrategy()
        );

        return new UserService($repository, $hydrator, $uuid);
    }
}
