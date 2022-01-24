<?php declare(strict_types=1);

namespace App\Handler;

use PHPUnit\Framework\TestCase;

class IndexHandlerTest extends TestCase
{
    public function testDoCan()
    {
        $this->assertSame('1', '1');
    }
}
