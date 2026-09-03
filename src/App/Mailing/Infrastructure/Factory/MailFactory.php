<?php declare(strict_types=1);

namespace App\Mailing\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;

final class MailFactory
{
    public function __invoke(ContainerInterface $container): MailerInterface
    {
        /** @var array{mailer: array{dsn: string}} $config */
        $config = $container->get('config');

        return new Mailer(Transport::fromDsn($config['mailer']['dsn']));
    }
}
