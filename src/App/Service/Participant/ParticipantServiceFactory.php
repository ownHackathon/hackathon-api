<?php declare(strict_types=1);

namespace App\Service\Participant;

use App\Entity\Participant;
use App\Hydrator\ReflectionHydrator;
use App\Repository\ParticipantRepository;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Psr\Container\ContainerInterface;

class ParticipantServiceFactory
{
    public function __invoke(ContainerInterface $container): ParticipantService
    {
        /** @var ParticipantRepository $repository */
        $repository = $container->get(ParticipantRepository::class);

        /** @var ReflectionHydrator $hydrator */
        $hydrator = clone $container->get(ReflectionHydrator::class);

        /** @var DateTimeFormatterStrategy $strategy */
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'requestTime',
            $strategy,
        );

        $hydrator->addStrategy(
            'participant',
            new HydratorStrategy($container->get(ReflectionHydrator::class), Participant::class)
        );

        return new ParticipantService($repository, $hydrator);
    }
}
