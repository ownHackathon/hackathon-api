<?php declare(strict_types=1);

namespace App\Test\Factory;

use App\Factory\MailFactory;
use App\Test\Mock\MockContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Mailer;

class MailFactoryTest extends TestCase
{
    public function testCanCreateMailInstance(): void
    {
        $config = [
            'mailer' => [
                'dsn' => 'smtp://example.com:1025',
                'from' => 'example@example.com',
            ],
        ];

        $container = new MockContainer();
        $container->add('config', $config);

        $mailer = (new MailFactory())($container);

        $this->assertInstanceOf(Mailer::class, $mailer);
    }
}
