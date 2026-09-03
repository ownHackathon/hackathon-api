<?php declare(strict_types=1);

namespace App\Mailing\Infrastructure\Factory;

use App\Mailing\Domain\EmailType;
use App\Mailing\Infrastructure\Service\EmailService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly final class EmailServiceFactory
{
    public function __invoke(ContainerInterface $container): EmailService
    {
        $mailer = $container->get(MailerInterface::class);

        /** @var array{project: array{senderEmail: string}} $config */
        $config = $container->get('config');
        $senderEmail = new EmailType($config['project']['senderEmail']);

        return new EmailService($mailer, $senderEmail);
    }
}
