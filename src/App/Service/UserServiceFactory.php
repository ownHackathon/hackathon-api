<?php declare(strict_types=1);

namespace App\Service;

use App\Enum\EventStatus;
use App\Hydrator\ReflectionHydrator;
use App\Table\UserTable;
use Laminas\Hydrator\Strategy\BackedEnumStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

class UserServiceFactory
{
    public function __invoke(ContainerInterface $container): UserService
    {
        $table = $container->get(UserTable::class);
        $hydrator = $container->get(ReflectionHydrator::class);
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
        $hydrator->addStrategy(
            'status',
            new BackedEnumStrategy(EventStatus::class)
        );

        return new UserService($table, $hydrator, $uuid);
    }
}
