<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Service\Email;

use Exdrals\Account\Identity\Domain\Email;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class EmailServiceFactory
{
    public function __invoke(ContainerInterface $container): EmailService
    {
        $mailer = $container->get(MailerInterface::class);
        $senderEmail = new Email($container->get('config')['project']['senderEmail']);

        return new EmailService($mailer, $senderEmail);
    }
}
