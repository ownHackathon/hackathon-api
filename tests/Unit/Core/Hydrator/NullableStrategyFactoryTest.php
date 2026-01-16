<?php declare(strict_types=1);

namespace Test\Unit\Core\Hydrator;

use Core\Hydrator\DateTimeFormatterStrategyFactory;
use Core\Hydrator\NullableStrategyFactory;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\MockContainer;

class NullableStrategyFactoryTest extends TestCase
{
    public function testCanCreateNullableStrategy(): void
    {
        $container = new MockContainer();
        $container->add(DateTimeFormatterStrategy::class, (new DateTimeFormatterStrategyFactory())($container));

        $nullableStragegy = (new NullableStrategyFactory())($container);

        self::assertInstanceOf(NullableStrategy::class, $nullableStragegy);
    }
}
