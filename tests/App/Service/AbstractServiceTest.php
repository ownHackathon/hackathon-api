<?php declare(strict_types=1);

namespace AppTest\Service;

use App\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;

abstract class AbstractServiceTest extends TestCase
{
    protected ReflectionHydrator $hydrator;
    protected array $fetchResult = ['id' => 1];
    protected array $fetchAllResult = [
        0 => ['id' => 1],
    ];

    protected function setUp(): void
    {
        $this->hydrator = new ReflectionHydrator();

        parent::setUp();
    }
}
