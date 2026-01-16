<?php declare(strict_types=1);

namespace UnitTest\CoreTest\Factory;

use Envms\FluentPDO\Query;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Core\Factory\QueryFactory;
use UnitTest\Mock\Database\MockPDO;
use UnitTest\Mock\MockContainer;

class QueryFactoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCanCreateQueryInstance(): void
    {
        $container = new MockContainer();
        $container->add(PDO::class, new MockPDO());

        $query = (new QueryFactory())($container);

        $this->assertInstanceOf(Query::class, $query);
    }
}
