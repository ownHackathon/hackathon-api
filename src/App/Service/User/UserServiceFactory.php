<?php declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Enum\UserRole;
use App\Hydrator\ReflectionHydrator;
use App\Repository\UserRepository;
use Laminas\Hydrator\Strategy\BackedEnumStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

class UserServiceFactory
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
            'registrationTime',
            $dateTimeFormatterStrategy,
        );

        $hydrator->addStrategy(
            'lastAction',
            $dateTimeFormatterStrategy,
        );

        $hydrator->addStrategy(
            'role',
            new BackedEnumStrategy(UserRole::class)
        );

        $hydrator->addStrategy(
            'user',
            new HydratorStrategy($container->get(ReflectionHydrator::class), User::class)
        );

        /** ToDo implements Uuid Strategy */

        return new UserService($repository, $hydrator, $uuid);
    }
}
