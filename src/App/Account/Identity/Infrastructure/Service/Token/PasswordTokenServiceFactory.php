<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Token;

use ownHackathon\App\Mailing\Application\Port\MailerInterface;
use Psr\Container\ContainerInterface;

readonly final class PasswordTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): PasswordTokenService
    {
        $emailService = $container->get(MailerInterface::class);

        /** @var array{project: array{uri: string}} $config */
        $config = $container->get('config');
        $projectUri = $config['project']['uri'];

        return new PasswordTokenService($emailService, $projectUri);
    }
}
