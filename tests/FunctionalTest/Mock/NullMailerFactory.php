<?php declare(strict_types=1);

namespace ownHackathon\FunctionalTest\Mock;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class NullMailerFactory
{
    public function __invoke(ContainerInterface $container): MailerInterface
    {
        return new NullMailer();
    }
}
