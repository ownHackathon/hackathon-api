<?php declare(strict_types=1);

namespace Test\Functional;

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @coversNothing
 */
abstract class FunctionalTestCase extends TestCase
{
    protected ContainerInterface $container;
    protected Application $app;

    protected function setUp(): void
    {
        parent::setUp();

        $container = require __DIR__ . '/../../config/container.php';

        $this->app = $container->get(Application::class);

        $factory = $container->get(MiddlewareFactory::class);

        (require __DIR__ . '/../../config/pipeline.php')($this->app);
        (require __DIR__ . '/../../config/routes.php')($this->app);
    }
}
