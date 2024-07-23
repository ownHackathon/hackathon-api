<?php declare(strict_types=1);

namespace Test\Unit\App\Factory;

use Core\Factory\MailFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Mailer;
use Test\Unit\Mock\MockContainer;

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

        self::assertInstanceOf(Mailer::class, $mailer);
    }
}
