<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractServiceTest extends TestCase
{
    protected ReflectionHydrator $hydrator;
    protected DateTimeFormatterStrategy $dateTimeFormatterStrategy;
    protected NullableStrategy $nullableStrategy;
    protected UuidInterface $uuid;
    protected array $fetchResult = ['id' => 1];
    protected array $fetchAllResult
        = [
            0 => ['id' => 1],
        ];

    protected function setUp(): void
    {
        $this->hydrator = new ReflectionHydrator();
        $this->dateTimeFormatterStrategy = new DateTimeFormatterStrategy();
        $this->nullableStrategy = new NullableStrategy($this->dateTimeFormatterStrategy);
        $this->uuid = Uuid::uuid4();
        parent::setUp();
    }
}
