<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\ParticipantService;
use App\Service\ParticipantServiceFactory;
use App\Table\ParticipantTable;
use Test\Unit\App\Mock\MockContainer;
use Test\Unit\App\Mock\Table\MockParticipantTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

class ParticipantServiceFactoryTest extends AbstractService
{
    public function testCanCreateParticipantService(): void
    {
        $container = new MockContainer([
            ParticipantTable::class => new MockParticipantTable(),
            ReflectionHydrator::class => $this->hydrator,
            DateTimeFormatterStrategy::class => $this->dateTimeFormatterStrategy,
        ]);

        $factory = new ParticipantServiceFactory();

        $service = $factory($container);

        $this->assertInstanceOf(ParticipantService::class, $service);
    }
}
