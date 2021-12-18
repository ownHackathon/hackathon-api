<?php declare(strict_types=1);

namespace App\Service;

use App\Table\ParticipantTable;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class ParticipantServiceFactory
{
    public function __invoke(ContainerInterface $container): ParticipantService
    {
        $table = $container->get(ParticipantTable::class);
        $hydrator = $container->get(ReflectionHydrator::class);
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'requestTime',
            $strategy,
        );

        return new ParticipantService($table, $hydrator);
    }
}
