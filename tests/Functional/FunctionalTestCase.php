<?php declare(strict_types=1);

namespace Test\Functional;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Trinet\MezzioTest\MezzioTestEnvironment;

abstract class FunctionalTestCase extends TestCase
{
    protected ContainerInterface $container;
    protected MezzioTestEnvironment $app;

    protected function setUp(): void
    {
        parent::setUp();
        $basePath = dirname(__DIR__);
        $this->app = new MezzioTestEnvironment($basePath);
    }
}
