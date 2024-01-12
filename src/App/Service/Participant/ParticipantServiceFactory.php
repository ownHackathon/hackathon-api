<?php declare(strict_types=1);

namespace App\Service\Participant;

use App\Hydrator\ReflectionHydrator;
use App\Repository\ParticipantRepository;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class ParticipantServiceFactory
{
    public function __invoke(ContainerInterface $container): ParticipantService
    {
        $repository = $container->get(ParticipantRepository::class);
        $hydrator = $container->get(ReflectionHydrator::class);
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'requestTime',
            $strategy,
        );

        return new ParticipantService($repository, $hydrator);
    }
}
