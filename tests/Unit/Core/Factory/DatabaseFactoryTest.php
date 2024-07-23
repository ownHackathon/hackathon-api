<?php declare(strict_types=1);

namespace Test\Unit\Core\Factory;

use Core\Factory\DatabaseFactory;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\MockContainer;

class DatabaseFactoryTest extends TestCase
{
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

            ],
        ];

        $container = new MockContainer();
        $container->add('config', $config);

        $pdo = (new DatabaseFactory())($container);

        self::assertInstanceOf(PDO::class, $pdo);
    }
}
