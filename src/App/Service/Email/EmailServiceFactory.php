<?php declare(strict_types=1);

namespace App\Service\Email;

use Core\Type\Email;
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
