<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\CoreTest\Factory;

use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ownHackathon\Core\Factory\DatabaseFactory;
use ownHackathon\UnitTest\Mock\MockContainer;

class DatabaseFactoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testThrowPDOException(): void
    {
        $this->expectException(PDOException::class);

        $config = [
            'database' => [
                'driver' => 'mysql',
                'user' => 'testUser',
                'password' => 'testPassword',
                'host' => 'https//example.com',
                'port' => 3306,
                'dbname' => 'example_db',
                'error' => PDO::ERRMODE_EXCEPTION,
                'emulate_prepares' => false,
            ],
        ];

        $container = new MockContainer();
        $container->add('config', $config);

        $pdo = (new DatabaseFactory())($container);

        $this->assertInstanceOf(PDO::class, $pdo);
    }
}
