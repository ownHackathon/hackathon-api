<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Repository\ParticipantRepository;
use App\Service\Participant\ParticipantService;
use App\Service\Participant\ParticipantServiceFactory;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Test\Unit\Mock\MockContainer;
use Test\Unit\Mock\Table\MockParticipantTable;

class ParticipantServiceFactoryTest extends AbstractService
{
    public function testCanCreateParticipantService(): void
    {
        $container = new MockContainer([
            ParticipantRepository::class => new MockParticipantTable(),
            ReflectionHydrator::class => $this->hydrator,
            DateTimeFormatterStrategy::class => $this->dateTimeFormatterStrategy,
        ]);

        $factory = new ParticipantServiceFactory();

        $service = $factory($container);

        self::assertInstanceOf(ParticipantService::class, $service);
    }
}
