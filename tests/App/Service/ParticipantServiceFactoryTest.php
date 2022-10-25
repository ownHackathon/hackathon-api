<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\ParticipantService;
use App\Service\ParticipantServiceFactory;
use App\Table\ParticipantTable;
use App\Test\Mock\MockContainer;
use App\Test\Mock\Table\MockParticipantTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

class ParticipantServiceFactoryTest extends AbstractServiceTest
{
    public function testCanCreateParticipantService(): void
    {
        $container = new MockContainer([
            ParticipantTable::class => new MockParticipantTable(),
            ReflectionHydrator::class => $this->hydrator,
            DateTimeFormatterStrategy::class => $this->strategy,
        ]);

        $factory = new ParticipantServiceFactory();

        $service = $factory($container);

        $this->assertInstanceOf(ParticipantService::class, $service);
    }
}
