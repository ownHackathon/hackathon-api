<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;

class MailFactory
{
    public function __invoke(ContainerInterface $container): MailerInterface
    {
        $settings = $container->get('config');

        $settings = $settings['mailer'];
        return new Mailer(Transport::fromDsn($settings['dsn']));
    }
}
