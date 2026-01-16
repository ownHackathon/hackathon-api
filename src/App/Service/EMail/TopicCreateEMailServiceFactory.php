<?php declare(strict_types=1);

namespace App\Service\EMail;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;

readonly class TopicCreateEMailServiceFactory
{
    public function __invoke(ContainerInterface $container): TopicCreateEMailService
    {
        /** @var Mailer $mailer */
        $mailer = $container->get(Mailer::class);

        $mailSender = $container->get('config')['mailer']['from'];
        $projectUri = $container->get('config')['project']['uri'];

        return new TopicCreateEMailService($mailer, $mailSender, $projectUri);
    }
}
