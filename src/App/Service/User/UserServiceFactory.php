<?php declare(strict_types=1);

namespace App\Service\User;

use App\Hydrator\ReflectionHydrator;
use App\Repository\UserRepository;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

class UserServiceFactory
{
    public function __invoke(ContainerInterface $container): UserService
    {
        $repository = $container->get(UserRepository::class);
        $hydrator = clone $container->get(ReflectionHydrator::class);
        $nullableStrategy = $container->get(NullableStrategy::class);
        $dateTimeFormatterStrategy = $container->get(DateTimeFormatterStrategy::class);
        $uuid = $container->get(Uuid::class);

        $hydrator->addStrategy(
            'registrationTime',
            $dateTimeFormatterStrategy,
        );
        $hydrator->addStrategy(
            'lastAction',
            $nullableStrategy,
        );

        return new UserService($repository, $hydrator, $uuid);
    }
}
