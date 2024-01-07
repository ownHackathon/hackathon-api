<?php declare(strict_types=1);

namespace Test\Unit\App\Hydrator;

use App\Hydrator\DateTimeFormatterStrategyFactory;
use App\Hydrator\NullableStrategyFactory;
use Test\Unit\App\Mock\MockContainer;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use PHPUnit\Framework\TestCase;

class NullableStrategyFactoryTest extends TestCase
{
    public function testCanCreateNullableStrategy(): void
    {
        $container = new MockContainer();
        $container->add(DateTimeFormatterStrategy::class, (new DateTimeFormatterStrategyFactory())($container));

        $nullableStragegy = (new NullableStrategyFactory())($container);

        $this->assertInstanceOf(NullableStrategy::class, $nullableStragegy);
    }
}
