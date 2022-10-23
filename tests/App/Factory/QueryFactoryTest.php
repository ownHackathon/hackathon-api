<?php declare(strict_types=1);

namespace App\Factory;

use App\Test\Mock\MockContainer;
use App\Test\Mock\MockPDO;
use Envms\FluentPDO\Query;
use PDO;
use PHPUnit\Framework\TestCase;

class QueryFactoryTest extends TestCase
{
    public function testCanCreateQueryInstance(): void
    {
        $container = new MockContainer();
        $container->add(PDO::class, new MockPDO());

        $query = (new QueryFactory())($container);

        $this->assertInstanceOf(Query::class, $query);
    }
}
