<?php declare(strict_types=1);

namespace App\Service;

use App\Table\UserTable;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Psr\Container\ContainerInterface;

class UserServiceFactory
{
    public function __invoke(ContainerInterface $container): UserService
    {
        $table = $container->get(UserTable::class);
        $hydrator = $container->get(ReflectionHydrator::class);
        $strategy = $container->get(NullableStrategy::class);

        $hydrator->addStrategy(
            'registrationTime',
            $strategy,
        );
        $hydrator->addStrategy(
            'lastLogin',
            $strategy,
        );

        return new UserService($table, $hydrator);
    }
}
