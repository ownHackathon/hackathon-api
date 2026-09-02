<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Token;

use ownHackathon\App\Mailing\Application\Port\MailerInterface;
use Psr\Container\ContainerInterface;

readonly final class PasswordTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): PasswordTokenService
    {
        $emailService = $container->get(MailerInterface::class);
        $projectUri = $container->get('config')['project']['uri'];

        return new PasswordTokenService($emailService, $projectUri);
    }
}
