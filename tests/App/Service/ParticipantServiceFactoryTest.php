<?php declare(strict_types=1);

namespace AppTest\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\ParticipantService;
use App\Service\ParticipantServiceFactory;
use App\Table\ParticipantTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class ParticipantServiceFactoryTest extends AbstractServiceTest
{
    public function testCanCreateParticipantService(): void
    {
        $table = $this->createMock(ParticipantTable::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(3))
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [ParticipantTable::class, $table],
                        [ReflectionHydrator::class, $this->hydrator],
                        [DateTimeFormatterStrategy::class, new DateTimeFormatterStrategy()],
                    ]
                )
            );

        $factory = new ParticipantServiceFactory();

        $service = $factory($container);

        $this->assertInstanceOf(ParticipantService::class, $service);
    }
}
