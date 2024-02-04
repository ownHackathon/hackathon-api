<?php declare(strict_types=1);

namespace App\Handler\Authentication;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;

readonly class UserPasswordForgottonHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserPasswordForgottonHandler
    {
        $mailer = $container->get(Mailer::class);
        $mailSender = $container->get('config')['mailer']['from'];
        $projectUri = $container->get('config')['project']['uri'];

        return new UserPasswordForgottonHandler($mailer, $mailSender, $projectUri);
    }
}
