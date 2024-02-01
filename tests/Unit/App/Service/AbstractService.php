<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Hydrator\ReflectionHydrator;
use App\System\Hydrator\Strategy\UuidStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractService extends TestCase
{
    protected ReflectionHydrator $hydrator;
    protected DateTimeFormatterStrategy $dateTimeFormatterStrategy;
    protected NullableStrategy $nullableStrategy;
    protected UuidStrategy $uuidStrategy;
    protected UuidInterface $uuid;

    protected function setUp(): void
    {
        $this->hydrator = new ReflectionHydrator();
        $this->dateTimeFormatterStrategy = new DateTimeFormatterStrategy();
        $this->nullableStrategy = new NullableStrategy($this->dateTimeFormatterStrategy);
        $this->uuidStrategy = new UuidStrategy();
        $this->uuid = Uuid::uuid4();
        parent::setUp();
    }
}
