<?php declare(strict_types=1);

namespace Test\Unit\App\Factory;

use Core\Factory\QueryFactory;
use Envms\FluentPDO\Query;
use PDO;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\Database\MockPDO;
use Test\Unit\Mock\MockContainer;

class QueryFactoryTest extends TestCase
{
    public function testCanCreateQueryInstance(): void
    {
        $container = new MockContainer();
        $container->add(PDO::class, new MockPDO());

        $query = (new QueryFactory())($container);

        self::assertInstanceOf(Query::class, $query);
    }
}
