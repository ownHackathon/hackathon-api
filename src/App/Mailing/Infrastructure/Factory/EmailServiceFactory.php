<?php declare(strict_types=1);

namespace ownHackathon\App\Mailing\Infrastructure\Factory;

use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Mailing\Infrastructure\Service\EmailService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly final class EmailServiceFactory
{
    public function __invoke(ContainerInterface $container): EmailService
    {
        $mailer = $container->get(MailerInterface::class);
        $senderEmail = new EmailType($container->get('config')['project']['senderEmail']);

        return new EmailService($mailer, $senderEmail);
    }
}
