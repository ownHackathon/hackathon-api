<?php declare(strict_types=1);

namespace Exdrals\Mailing\Infrastructure;

use Exdrals\Mailing\Domain\EmailType;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class EmailServiceFactory
{
    public function __invoke(ContainerInterface $container): EmailService
    {
        $mailer = $container->get(MailerInterface::class);
        $senderEmail = new EmailType($container->get('config')['project']['senderEmail']);

        return new EmailService($mailer, $senderEmail);
    }
}
