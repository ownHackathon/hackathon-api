<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use PHPUnit\Framework\TestCase;

abstract class AbstractServiceTest extends TestCase
{
    protected ReflectionHydrator $hydrator;
    protected DateTimeFormatterStrategy $strategy;
    protected array $fetchResult = ['id' => 1];
    protected array $fetchAllResult
        = [
            0 => ['id' => 1],
        ];

    protected function setUp(): void
    {
        $this->hydrator = new ReflectionHydrator();
        $this->strategy = new DateTimeFormatterStrategy();
        parent::setUp();
    }
}
