<?php declare(strict_types=1);

namespace App\Service\User;

use App\Hydrator\ReflectionHydrator;
use App\Repository\UserRepository;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

class UserServiceFactory
{
    public function __invoke(ContainerInterface $container): UserService
    {
        $repository = $container->get(UserRepository::class);
        $hydrator = clone $container->get(ReflectionHydrator::class);
        $strategy = $container->get(NullableStrategy::class);
        $uuid = $container->get(Uuid::class);

        $hydrator->addStrategy(
            'registrationTime',
            $strategy,
        );
        $hydrator->addStrategy(
            'lastAction',
            $strategy,
        );

        return new UserService($repository, $hydrator, $uuid);
    }
}
