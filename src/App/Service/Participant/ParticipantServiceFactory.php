<?php declare(strict_types=1);

namespace App\Service\Participant;

use App\Hydrator\ReflectionHydrator;
use App\Repository\ParticipantRepository;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

readonly class ParticipantServiceFactory
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

        return new ParticipantService($repository, $hydrator);
    }
}
