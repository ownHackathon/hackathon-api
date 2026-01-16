<?php declare(strict_types=1);

namespace Core\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

readonly class MailFactory
{
    public function __invoke(ContainerInterface $container): Mailer
    {
        $settings = $container->get('config');

        $settings = $settings['mailer'];
        return new Mailer(Transport::fromDsn($settings['dsn']));
    }
}
