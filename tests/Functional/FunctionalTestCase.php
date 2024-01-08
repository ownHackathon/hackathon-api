<?php declare(strict_types=1);

namespace Test\Functional;

use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase;
use Trinet\MezzioTest\MezzioTestEnvironment;

abstract class FunctionalTestCase extends TestCase
{
    use JsonAssertions;
    use JsonRequestHelper;

    protected MezzioTestEnvironment $app;

    protected function setUp(): void
    {
        parent::setUp();
        $basePath = dirname(__DIR__);
        $this->app = new MezzioTestEnvironment($basePath);
    }
}
