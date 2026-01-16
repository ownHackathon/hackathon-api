<?php declare(strict_types=1);

namespace Test\Unit\Core\Factory;

use Core\Factory\DatabaseFactory;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\MockContainer;

use function dirname;

class DatabaseFactoryTest extends TestCase
{
    public function testThrowPdoException(): void
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

        (new DatabaseFactory())($container);
    }

    public function testCanInitiatePdoConnection(): void
    {
        system('touch ' . dirname(__FILE__) . '/../../../../database/database.sqlite');
        $config = require dirname(__FILE__) . '/../../../config/autoload/database.testing.local.php';

        $container = new MockContainer();
        $container->add('config', $config);

        $pdo = (new DatabaseFactory())($container);

        self::assertInstanceOf(PDO::class, $pdo);
    }

    public function tearDown(): void
    {
        system('rm ' . dirname(__FILE__) . '/../../../../database/database.sqlite');
    }
}
